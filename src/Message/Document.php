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

final readonly class Document
{
    public function __construct(
        private string $to,
        private ?string $link = null,
        private ?string $mediaId = null,
        private ?string $caption = null,
        private ?string $filename = null,
    ) {}

    public function toArray(): array
    {
        $document = [];

        if ($this->mediaId !== null) {
            $document['id'] = $this->mediaId;
        } else {
            $document['link'] = $this->link;
        }

        if ($this->caption !== null) {
            $document['caption'] = $this->caption;
        }

        if ($this->filename !== null) {
            $document['filename'] = $this->filename;
        }

        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'document',
            'document' => $document,
        ];
    }
}
