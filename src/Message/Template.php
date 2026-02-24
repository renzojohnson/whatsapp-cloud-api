<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Message;

final readonly class Template
{
    /**
     * @param array<array{type: string, parameters: array<array{type: string, text?: string, image?: array, document?: array, video?: array}>}> $components
     */
    public function __construct(
        private string $to,
        private string $name,
        private string $languageCode = 'en_US',
        private array $components = [],
    ) {}

    public function toArray(): array
    {
        $template = [
            'name' => $this->name,
            'language' => [
                'code' => $this->languageCode,
            ],
        ];

        if ($this->components !== []) {
            $template['components'] = $this->components;
        }

        return [
            'messaging_product' => 'whatsapp',
            'to' => $this->to,
            'type' => 'template',
            'template' => $template,
        ];
    }
}
