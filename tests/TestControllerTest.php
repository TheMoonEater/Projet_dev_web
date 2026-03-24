<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Controllers\TestController;

final class TestControllerTest extends TestCase
{
    public function testPingOutputsPong(): void
    {
        $controller = new TestController();

        ob_start();
        $controller->ping();
        $out = ob_get_clean();

        $this->assertSame('pong', $out);
    }
}