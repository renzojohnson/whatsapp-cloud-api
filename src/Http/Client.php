<?php

/**
 * WhatsApp Cloud API
 *
 * @package   RenzoJohnson\WhatsApp
 * @author    Renzo Johnson <hello@renzojohnson.com>
 * @copyright 2026 Renzo Johnson
 * @license   MIT
 * @link      https://renzojohnson.com
 */

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Http;

use RenzoJohnson\WhatsApp\Exception\AuthenticationException;
use RenzoJohnson\WhatsApp\Exception\RateLimitException;
use RenzoJohnson\WhatsApp\Exception\WhatsAppException;

final class Client
{
    private const BASE_URL = 'https://graph.facebook.com/v21.0';

    public function __construct(
        private readonly string $accessToken,
        private readonly int $timeout = 30,
    ) {}

    public function post(string $endpoint, array $payload): array
    {
        return $this->request('POST', $endpoint, $payload);
    }

    public function get(string $endpoint): array
    {
        return $this->request('GET', $endpoint);
    }

    public function delete(string $endpoint): array
    {
        return $this->request('DELETE', $endpoint);
    }

    public function uploadMedia(string $endpoint, string $filePath, string $mimeType): array
    {
        $ch = curl_init(self::BASE_URL . $endpoint);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->accessToken,
            ],
            CURLOPT_POSTFIELDS => [
                'messaging_product' => 'whatsapp',
                'file' => new \CURLFile($filePath, $mimeType),
            ],
        ]);

        return $this->execute($ch);
    }

    private function request(string $method, string $endpoint, ?array $payload = null): array
    {
        $url = self::BASE_URL . $endpoint;
        $ch = curl_init($url);

        $headers = [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json',
        ];

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if ($method === 'POST' && $payload !== null) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = json_encode($payload, JSON_THROW_ON_ERROR);
        }

        if ($method === 'DELETE') {
            $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        }

        curl_setopt_array($ch, $options);

        return $this->execute($ch);
    }

    private function execute(\CurlHandle $ch): array
    {
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new WhatsAppException('cURL error: ' . $error);
        }

        $decoded = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($httpCode === 401) {
            throw new AuthenticationException(
                $decoded['error']['message'] ?? 'Invalid access token',
                401,
                $decoded['error'] ?? [],
            );
        }

        if ($httpCode === 429) {
            throw new RateLimitException(
                $decoded['error']['message'] ?? 'Rate limit exceeded',
                429,
                $decoded['error'] ?? [],
            );
        }

        if ($httpCode >= 400) {
            throw new WhatsAppException(
                $decoded['error']['message'] ?? 'API error',
                $httpCode,
                $decoded['error'] ?? [],
            );
        }

        return $decoded;
    }
}
