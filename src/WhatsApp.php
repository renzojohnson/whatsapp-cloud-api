<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp;

use RenzoJohnson\WhatsApp\Http\Client;
use RenzoJohnson\WhatsApp\Message\Audio;
use RenzoJohnson\WhatsApp\Message\Contact;
use RenzoJohnson\WhatsApp\Message\Document;
use RenzoJohnson\WhatsApp\Message\Image;
use RenzoJohnson\WhatsApp\Message\Location;
use RenzoJohnson\WhatsApp\Message\Sticker;
use RenzoJohnson\WhatsApp\Message\Template;
use RenzoJohnson\WhatsApp\Message\Text;
use RenzoJohnson\WhatsApp\Message\Video;

final class WhatsApp
{
    private Client $client;

    private string $endpoint;

    public function __construct(
        private readonly string $phoneNumberId,
        string $accessToken,
        int $timeout = 30,
    ) {
        $this->client = new Client($accessToken, $timeout);
        $this->endpoint = '/' . $phoneNumberId . '/messages';
    }

    public function sendText(string $to, string $body, bool $previewUrl = false): array
    {
        $message = new Text($to, $body, $previewUrl);

        return $this->client->post($this->endpoint, $message->toArray());
    }

    /**
     * @param array<array{type: string, parameters: array}> $components
     */
    public function sendTemplate(
        string $to,
        string $templateName,
        string $languageCode = 'en_US',
        array $components = [],
    ): array {
        $message = new Template($to, $templateName, $languageCode, $components);

        return $this->client->post($this->endpoint, $message->toArray());
    }

    public function sendImage(
        string $to,
        ?string $link = null,
        ?string $mediaId = null,
        ?string $caption = null,
    ): array {
        $message = new Image($to, $link, $mediaId, $caption);

        return $this->client->post($this->endpoint, $message->toArray());
    }

    public function sendDocument(
        string $to,
        ?string $link = null,
        ?string $mediaId = null,
        ?string $caption = null,
        ?string $filename = null,
    ): array {
        $message = new Document($to, $link, $mediaId, $caption, $filename);

        return $this->client->post($this->endpoint, $message->toArray());
    }

    public function sendVideo(
        string $to,
        ?string $link = null,
        ?string $mediaId = null,
        ?string $caption = null,
    ): array {
        $message = new Video($to, $link, $mediaId, $caption);

        return $this->client->post($this->endpoint, $message->toArray());
    }

    public function sendAudio(
        string $to,
        ?string $link = null,
        ?string $mediaId = null,
    ): array {
        $message = new Audio($to, $link, $mediaId);

        return $this->client->post($this->endpoint, $message->toArray());
    }

    public function sendLocation(
        string $to,
        float $latitude,
        float $longitude,
        ?string $name = null,
        ?string $address = null,
    ): array {
        $message = new Location($to, $latitude, $longitude, $name, $address);

        return $this->client->post($this->endpoint, $message->toArray());
    }

    /**
     * @param array{first_name: string, last_name?: string, formatted_name?: string} $name
     * @param array<array{phone: string, type?: string}> $phones
     */
    public function sendContact(
        string $to,
        array $name,
        array $phones = [],
    ): array {
        $message = new Contact($to, $name, $phones);

        return $this->client->post($this->endpoint, $message->toArray());
    }

    public function sendSticker(
        string $to,
        ?string $link = null,
        ?string $mediaId = null,
    ): array {
        $message = new Sticker($to, $link, $mediaId);

        return $this->client->post($this->endpoint, $message->toArray());
    }

    public function uploadMedia(string $filePath, string $mimeType): array
    {
        return $this->client->uploadMedia('/' . $this->phoneNumberId . '/media', $filePath, $mimeType);
    }

    public function getMedia(string $mediaId): array
    {
        return $this->client->get('/' . $mediaId);
    }

    public function deleteMedia(string $mediaId): array
    {
        return $this->client->delete('/' . $mediaId);
    }

    public function markAsRead(string $messageId): array
    {
        return $this->client->post($this->endpoint, [
            'messaging_product' => 'whatsapp',
            'status' => 'read',
            'message_id' => $messageId,
        ]);
    }
}
