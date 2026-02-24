<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Message;

final readonly class Text
{
    public function __construct(
        private string $to,
        private string $body,
        private bool $previewUrl = false,
    ) {}

    public function toArray(): array
    {
        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'text',
            'text' => [
                'preview_url' => $this->previewUrl,
                'body' => $this->body,
            ],
        ];
    }
}
