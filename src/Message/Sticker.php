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

namespace RenzoJohnson\WhatsApp\Message;

final readonly class Sticker
{
    public function __construct(
        private string $to,
        private ?string $link = null,
        private ?string $mediaId = null,
    ) {}

    public function toArray(): array
    {
        $sticker = [];

        if ($this->mediaId !== null) {
            $sticker['id'] = $this->mediaId;
        } else {
            $sticker['link'] = $this->link;
        }

        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'sticker',
            'sticker' => $sticker,
        ];
    }
}
