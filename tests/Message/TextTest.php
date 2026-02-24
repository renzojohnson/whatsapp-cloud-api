<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Tests\Message;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\WhatsApp\Message\Text;

final class TextTest extends TestCase
{
    public function testToArrayReturnsCorrectStructure(): void
    {
        $message = new Text('14155551234', 'Hello World');
        $result = $message->toArray();

        $this->assertSame('whatsapp', $result['messaging_product']);
        $this->assertSame('individual', $result['recipient_type']);
        $this->assertSame('14155551234', $result['to']);
        $this->assertSame('text', $result['type']);
        $this->assertSame('Hello World', $result['text']['body']);
        $this->assertFalse($result['text']['preview_url']);
    }

    public function testPreviewUrlEnabled(): void
    {
        $message = new Text('14155551234', 'Check https://example.com', true);
        $result = $message->toArray();

        $this->assertTrue($result['text']['preview_url']);
    }
}
