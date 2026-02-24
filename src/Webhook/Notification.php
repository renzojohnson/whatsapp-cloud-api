<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Webhook;

final readonly class Notification
{
    public function __construct(
        public string $from,
        public string $type,
        public string $timestamp,
        public ?string $messageId = null,
        public ?string $text = null,
        public ?array $image = null,
        public ?array $document = null,
        public ?array $video = null,
        public ?array $audio = null,
        public ?array $sticker = null,
        public ?array $location = null,
        public ?array $contacts = null,
        public ?array $interactive = null,
        public ?array $button = null,
        public ?array $context = null,
        public ?array $rawMessage = null,
    ) {}

    public static function fromPayload(array $payload): ?self
    {
        $entry = $payload['entry'][0] ?? null;

        if ($entry === null) {
            return null;
        }

        $changes = $entry['changes'][0] ?? null;

        if ($changes === null) {
            return null;
        }

        $value = $changes['value'] ?? [];
        $messages = $value['messages'] ?? [];

        if ($messages === []) {
            return null;
        }

        $message = $messages[0];
        $type = $message['type'] ?? 'unknown';

        return new self(
            from: $message['from'] ?? '',
            type: $type,
            timestamp: $message['timestamp'] ?? '',
            messageId: $message['id'] ?? null,
            text: $message['text']['body'] ?? null,
            image: $message['image'] ?? null,
            document: $message['document'] ?? null,
            video: $message['video'] ?? null,
            audio: $message['audio'] ?? null,
            sticker: $message['sticker'] ?? null,
            location: $message['location'] ?? null,
            contacts: $message['contacts'] ?? null,
            interactive: $message['interactive'] ?? null,
            button: $message['button'] ?? null,
            context: $message['context'] ?? null,
            rawMessage: $message,
        );
    }

    public function isText(): bool
    {
        return $this->type === 'text';
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isDocument(): bool
    {
        return $this->type === 'document';
    }

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    public function isAudio(): bool
    {
        return $this->type === 'audio';
    }

    public function isLocation(): bool
    {
        return $this->type === 'location';
    }

    public function isSticker(): bool
    {
        return $this->type === 'sticker';
    }

    public function isInteractive(): bool
    {
        return $this->type === 'interactive';
    }
}
