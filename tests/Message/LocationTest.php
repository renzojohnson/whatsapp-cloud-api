<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Tests\Message;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\WhatsApp\Message\Location;

final class LocationTest extends TestCase
{
    public function testLocationWithCoordinatesOnly(): void
    {
        $message = new Location('14155551234', 28.5383, -81.3792);
        $result = $message->toArray();

        $this->assertSame('location', $result['type']);
        $this->assertSame(28.5383, $result['location']['latitude']);
        $this->assertSame(-81.3792, $result['location']['longitude']);
        $this->assertArrayNotHasKey('name', $result['location']);
        $this->assertArrayNotHasKey('address', $result['location']);
    }

    public function testLocationWithNameAndAddress(): void
    {
        $message = new Location(
            '14155551234',
            28.5383,
            -81.3792,
            name: 'Orlando Office',
            address: '123 Main St, Orlando, FL',
        );
        $result = $message->toArray();

        $this->assertSame('Orlando Office', $result['location']['name']);
        $this->assertSame('123 Main St, Orlando, FL', $result['location']['address']);
    }
}
