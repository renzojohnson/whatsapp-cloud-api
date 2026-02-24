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

final readonly class Video
{
    public function __construct(
        private string $to,
        private ?string $link = null,
        private ?string $mediaId = null,
        private ?string $caption = null,
    ) {}

    public function toArray(): array
    {
        $video = [];

        if ($this->mediaId !== null) {
            $video['id'] = $this->mediaId;
        } else {
            $video['link'] = $this->link;
        }

        if ($this->caption !== null) {
            $video['caption'] = $this->caption;
        }

        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'video',
            'video' => $video,
        ];
    }
}
