<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Message;

final readonly class Location
{
    public function __construct(
        private string $to,
        private float $latitude,
        private float $longitude,
        private ?string $name = null,
        private ?string $address = null,
    ) {}

    public function toArray(): array
    {
        $location = [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];

        if ($this->name !== null) {
            $location['name'] = $this->name;
        }

        if ($this->address !== null) {
            $location['address'] = $this->address;
        }

        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'location',
            'location' => $location,
        ];
    }
}
