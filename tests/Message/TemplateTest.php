<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Tests\Message;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\WhatsApp\Message\Template;

final class TemplateTest extends TestCase
{
    public function testBasicTemplate(): void
    {
        $message = new Template('14155551234', 'hello_world', 'en_US');
        $result = $message->toArray();

        $this->assertSame('whatsapp', $result['messaging_product']);
        $this->assertSame('14155551234', $result['to']);
        $this->assertSame('template', $result['type']);
        $this->assertSame('hello_world', $result['template']['name']);
        $this->assertSame('en_US', $result['template']['language']['code']);
        $this->assertArrayNotHasKey('components', $result['template']);
    }

    public function testTemplateWithComponents(): void
    {
        $components = [
            [
                'type' => 'body',
                'parameters' => [
                    ['type' => 'text', 'text' => 'John'],
                    ['type' => 'text', 'text' => '12345'],
                ],
            ],
        ];

        $message = new Template('14155551234', 'order_update', 'en_US', $components);
        $result = $message->toArray();

        $this->assertCount(1, $result['template']['components']);
        $this->assertSame('body', $result['template']['components'][0]['type']);
        $this->assertSame('John', $result['template']['components'][0]['parameters'][0]['text']);
    }
}
