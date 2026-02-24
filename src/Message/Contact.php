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

final readonly class Contact
{
    /**
     * @param array{first_name: string, last_name?: string, formatted_name?: string} $name
     * @param array<array{phone: string, type?: string}> $phones
     */
    public function __construct(
        private string $to,
        private array $name,
        private array $phones = [],
    ) {}

    public function toArray(): array
    {
        $contactName = [
            'first_name' => $this->name['first_name'],
        ];

        if (isset($this->name['last_name'])) {
            $contactName['last_name'] = $this->name['last_name'];
        }

        $contactName['formatted_name'] = $this->name['formatted_name']
            ?? trim(($this->name['first_name'] ?? '') . ' ' . ($this->name['last_name'] ?? ''));

        $contact = ['name' => $contactName];

        if ($this->phones !== []) {
            $contact['phones'] = $this->phones;
        }

        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'contacts',
            'contacts' => [$contact],
        ];
    }
}
