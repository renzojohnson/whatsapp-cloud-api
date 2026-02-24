<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Tests\Message;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\WhatsApp\Message\Contact;

final class ContactTest extends TestCase
{
    public function testContactWithPhones(): void
    {
        $message = new Contact(
            '14155551234',
            name: ['first_name' => 'John', 'last_name' => 'Doe'],
            phones: [['phone' => '+14155559999', 'type' => 'CELL']],
        );
        $result = $message->toArray();

        $this->assertSame('contacts', $result['type']);
        $this->assertCount(1, $result['contacts']);
        $this->assertSame('John', $result['contacts'][0]['name']['first_name']);
        $this->assertSame('Doe', $result['contacts'][0]['name']['last_name']);
        $this->assertSame('John Doe', $result['contacts'][0]['name']['formatted_name']);
        $this->assertSame('+14155559999', $result['contacts'][0]['phones'][0]['phone']);
    }

    public function testContactFormattedNameGenerated(): void
    {
        $message = new Contact(
            '14155551234',
            name: ['first_name' => 'Jane'],
        );
        $result = $message->toArray();

        $this->assertSame('Jane', $result['contacts'][0]['name']['formatted_name']);
    }
}
