<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Tests\Webhook;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\WhatsApp\Webhook\Listener;
use RenzoJohnson\WhatsApp\Webhook\Notification;

final class ListenerTest extends TestCase
{
    public function testValidateSignatureWithValidSignature(): void
    {
        $payload = '{"entry":[]}';
        $secret = 'my_app_secret';
        $signature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        $this->assertTrue(Listener::validateSignature($payload, $signature, $secret));
    }

    public function testValidateSignatureWithInvalidSignature(): void
    {
        $payload = '{"entry":[]}';
        $secret = 'my_app_secret';
        $signature = 'sha256=invalid_signature';

        $this->assertFalse(Listener::validateSignature($payload, $signature, $secret));
    }

    public function testValidateSignatureWithEmptySignature(): void
    {
        $this->assertFalse(Listener::validateSignature('{}', '', 'secret'));
    }

    public function testNotificationFromTextPayload(): void
    {
        $payload = [
            'object' => 'whatsapp_business_account',
            'entry' => [
                [
                    'id' => '123456',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '14155551234',
                                    'phone_number_id' => '987654',
                                ],
                                'messages' => [
                                    [
                                        'from' => '14155559999',
                                        'id' => 'wamid.abc123',
                                        'timestamp' => '1708900000',
                                        'type' => 'text',
                                        'text' => ['body' => 'Hello there'],
                                    ],
                                ],
                            ],
                            'field' => 'messages',
                        ],
                    ],
                ],
            ],
        ];

        $notification = Notification::fromPayload($payload);

        $this->assertNotNull($notification);
        $this->assertSame('14155559999', $notification->from);
        $this->assertSame('text', $notification->type);
        $this->assertSame('Hello there', $notification->text);
        $this->assertSame('wamid.abc123', $notification->messageId);
        $this->assertTrue($notification->isText());
        $this->assertFalse($notification->isImage());
    }

    public function testNotificationFromImagePayload(): void
    {
        $payload = [
            'entry' => [
                [
                    'changes' => [
                        [
                            'value' => [
                                'messages' => [
                                    [
                                        'from' => '14155559999',
                                        'id' => 'wamid.img456',
                                        'timestamp' => '1708900000',
                                        'type' => 'image',
                                        'image' => [
                                            'mime_type' => 'image/jpeg',
                                            'sha256' => 'abc123',
                                            'id' => 'media_789',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $notification = Notification::fromPayload($payload);

        $this->assertNotNull($notification);
        $this->assertTrue($notification->isImage());
        $this->assertSame('media_789', $notification->image['id']);
    }

    public function testNotificationReturnsNullForEmptyPayload(): void
    {
        $this->assertNull(Notification::fromPayload([]));
        $this->assertNull(Notification::fromPayload(['entry' => []]));
        $this->assertNull(Notification::fromPayload(['entry' => [['changes' => []]]]));
    }
}
