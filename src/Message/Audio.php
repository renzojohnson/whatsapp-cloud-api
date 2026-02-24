<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Message;

final readonly class Audio
{
    public function __construct(
        private string $to,
        private ?string $link = null,
        private ?string $mediaId = null,
    ) {}

    public function toArray(): array
    {
        $audio = [];

        if ($this->mediaId !== null) {
            $audio['id'] = $this->mediaId;
        } else {
            $audio['link'] = $this->link;
        }

        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'audio',
            'audio' => $audio,
        ];
    }
}
