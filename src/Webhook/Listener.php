<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Webhook;

use RenzoJohnson\WhatsApp\Exception\WhatsAppException;

final class Listener
{
    public static function verify(string $verifyToken): void
    {
        $mode = $_GET['hub_mode'] ?? $_GET['hub.mode'] ?? '';
        $token = $_GET['hub_verify_token'] ?? $_GET['hub.verify_token'] ?? '';
        $challenge = $_GET['hub_challenge'] ?? $_GET['hub.challenge'] ?? '';

        if ($mode === 'subscribe' && $token === $verifyToken) {
            http_response_code(200);
            echo $challenge;
            exit;
        }

        http_response_code(403);
        exit;
    }

    public static function listen(string $appSecret, callable $handler): void
    {
        $payload = file_get_contents('php://input');

        if ($payload === '' || $payload === false) {
            http_response_code(400);
            return;
        }

        $signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

        if (!self::validateSignature($payload, $signature, $appSecret)) {
            http_response_code(401);
            return;
        }

        $data = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

        $notification = Notification::fromPayload($data);

        if ($notification !== null) {
            $handler($notification, $data);
        }

        http_response_code(200);
    }

    public static function validateSignature(string $payload, string $signature, string $secret): bool
    {
        if ($signature === '') {
            return false;
        }

        $expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }
}
