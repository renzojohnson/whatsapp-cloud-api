<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Tests\Message;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\WhatsApp\Message\Audio;
use RenzoJohnson\WhatsApp\Message\Document;
use RenzoJohnson\WhatsApp\Message\Image;
use RenzoJohnson\WhatsApp\Message\Sticker;
use RenzoJohnson\WhatsApp\Message\Video;

final class MediaTest extends TestCase
{
    public function testImageWithLink(): void
    {
        $message = new Image('14155551234', link: 'https://example.com/photo.jpg', caption: 'A photo');
        $result = $message->toArray();

        $this->assertSame('image', $result['type']);
        $this->assertSame('https://example.com/photo.jpg', $result['image']['link']);
        $this->assertSame('A photo', $result['image']['caption']);
        $this->assertArrayNotHasKey('id', $result['image']);
    }

    public function testImageWithMediaId(): void
    {
        $message = new Image('14155551234', mediaId: 'media_123');
        $result = $message->toArray();

        $this->assertSame('media_123', $result['image']['id']);
        $this->assertArrayNotHasKey('link', $result['image']);
    }

    public function testDocumentWithFilename(): void
    {
        $message = new Document(
            '14155551234',
            link: 'https://example.com/invoice.pdf',
            caption: 'Your invoice',
            filename: 'invoice-2026.pdf',
        );
        $result = $message->toArray();

        $this->assertSame('document', $result['type']);
        $this->assertSame('invoice-2026.pdf', $result['document']['filename']);
        $this->assertSame('Your invoice', $result['document']['caption']);
    }

    public function testVideoWithLink(): void
    {
        $message = new Video('14155551234', link: 'https://example.com/video.mp4');
        $result = $message->toArray();

        $this->assertSame('video', $result['type']);
        $this->assertSame('https://example.com/video.mp4', $result['video']['link']);
    }

    public function testAudioWithMediaId(): void
    {
        $message = new Audio('14155551234', mediaId: 'audio_456');
        $result = $message->toArray();

        $this->assertSame('audio', $result['type']);
        $this->assertSame('audio_456', $result['audio']['id']);
    }

    public function testStickerWithLink(): void
    {
        $message = new Sticker('14155551234', link: 'https://example.com/sticker.webp');
        $result = $message->toArray();

        $this->assertSame('sticker', $result['type']);
        $this->assertSame('https://example.com/sticker.webp', $result['sticker']['link']);
    }
}
