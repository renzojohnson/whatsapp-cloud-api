<?php

declare(strict_types=1);

namespace RenzoJohnson\WhatsApp\Tests;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\WhatsApp\WhatsApp;

final class WhatsAppTest extends TestCase
{
    public function testInstantiation(): void
    {
        $wa = new WhatsApp('123456789', 'test_access_token');

        $this->assertInstanceOf(WhatsApp::class, $wa);
    }
}
