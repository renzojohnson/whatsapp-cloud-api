# WhatsApp Cloud API for PHP

Lightweight PHP wrapper for [Meta's WhatsApp Cloud API](https://developers.facebook.com/docs/whatsapp/cloud-api). Zero dependencies.

Send text, template, image, document, video, audio, location, contact, and sticker messages. Receive and verify webhooks.

## Requirements

- PHP 8.4+
- ext-curl
- ext-json

## Installation

```bash
composer require renzojohnson/whatsapp-cloud-api
```

## Quick Start

```php
use RenzoJohnson\WhatsApp\WhatsApp;

$wa = new WhatsApp('YOUR_PHONE_NUMBER_ID', 'YOUR_ACCESS_TOKEN');

// Send a text message
$wa->sendText('14155551234', 'Hello from PHP!');
```

## Sending Messages

### Text

```php
$wa->sendText('14155551234', 'Hello World');

// With link preview
$wa->sendText('14155551234', 'Check https://example.com', previewUrl: true);
```

### Template

```php
// Simple template (no parameters)
$wa->sendTemplate('14155551234', 'hello_world', 'en_US');

// Template with parameters
$wa->sendTemplate('14155551234', 'order_update', 'en_US', [
    [
        'type' => 'body',
        'parameters' => [
            ['type' => 'text', 'text' => 'John'],
            ['type' => 'text', 'text' => 'ORD-12345'],
        ],
    ],
]);
```

### Image

```php
// By URL
$wa->sendImage('14155551234', link: 'https://example.com/photo.jpg', caption: 'A photo');

// By media ID (uploaded via uploadMedia)
$wa->sendImage('14155551234', mediaId: 'media_123');
```

### Document

```php
$wa->sendDocument(
    '14155551234',
    link: 'https://example.com/invoice.pdf',
    caption: 'Your invoice',
    filename: 'invoice-2026.pdf',
);
```

### Video

```php
$wa->sendVideo('14155551234', link: 'https://example.com/clip.mp4', caption: 'Watch this');
```

### Audio

```php
$wa->sendAudio('14155551234', link: 'https://example.com/audio.mp3');
```

### Location

```php
$wa->sendLocation(
    '14155551234',
    latitude: 28.5383,
    longitude: -81.3792,
    name: 'Orlando Office',
    address: '123 Main St, Orlando, FL',
);
```

### Contact

```php
$wa->sendContact(
    '14155551234',
    name: ['first_name' => 'Jane', 'last_name' => 'Doe'],
    phones: [['phone' => '+14155559999', 'type' => 'CELL']],
);
```

### Sticker

```php
$wa->sendSticker('14155551234', link: 'https://example.com/sticker.webp');
```

## Media

### Upload

```php
$response = $wa->uploadMedia('/path/to/file.jpg', 'image/jpeg');
$mediaId = $response['id'];
```

### Get Media URL

```php
$media = $wa->getMedia('media_123');
$url = $media['url'];
```

### Delete

```php
$wa->deleteMedia('media_123');
```

## Mark as Read

```php
$wa->markAsRead('wamid.abc123');
```

## Webhooks

### Verification (GET request from Meta)

```php
use RenzoJohnson\WhatsApp\Webhook\Listener;

// Responds to Meta's verification challenge
Listener::verify('YOUR_VERIFY_TOKEN');
```

### Receiving Messages (POST from Meta)

```php
use RenzoJohnson\WhatsApp\Webhook\Listener;
use RenzoJohnson\WhatsApp\Webhook\Notification;

Listener::listen('YOUR_APP_SECRET', function (Notification $notification, array $raw) {
    if ($notification->isText()) {
        echo 'From: ' . $notification->from . "\n";
        echo 'Text: ' . $notification->text . "\n";
    }

    if ($notification->isImage()) {
        echo 'Image ID: ' . $notification->image['id'] . "\n";
    }
});
```

The listener validates the `X-Hub-Signature-256` header using HMAC-SHA256 before processing. Invalid signatures return 401.

### Notification Properties

| Property | Type | Description |
|----------|------|-------------|
| `from` | string | Sender's phone number |
| `type` | string | Message type (text, image, document, etc.) |
| `timestamp` | string | Unix timestamp |
| `messageId` | ?string | WhatsApp message ID |
| `text` | ?string | Text body (when type is text) |
| `image` | ?array | Image data (id, mime_type, sha256) |
| `document` | ?array | Document data |
| `video` | ?array | Video data |
| `audio` | ?array | Audio data |
| `location` | ?array | Location data (latitude, longitude) |
| `sticker` | ?array | Sticker data |
| `contacts` | ?array | Contact cards |
| `interactive` | ?array | Interactive response data |
| `context` | ?array | Reply context (quoted message) |

### Type Checks

```php
$notification->isText();
$notification->isImage();
$notification->isDocument();
$notification->isVideo();
$notification->isAudio();
$notification->isLocation();
$notification->isSticker();
$notification->isInteractive();
```

## Error Handling

```php
use RenzoJohnson\WhatsApp\Exception\AuthenticationException;
use RenzoJohnson\WhatsApp\Exception\RateLimitException;
use RenzoJohnson\WhatsApp\Exception\WhatsAppException;

try {
    $wa->sendText('14155551234', 'Hello');
} catch (AuthenticationException $e) {
    // Invalid or expired access token (401)
} catch (RateLimitException $e) {
    // Too many requests (429)
} catch (WhatsAppException $e) {
    // Other API errors (400, 500, etc.)
    $errorData = $e->getErrorData();
}
```

## Testing

```bash
composer install
vendor/bin/phpunit
```

## License

MIT
