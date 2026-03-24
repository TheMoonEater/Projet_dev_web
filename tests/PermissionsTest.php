<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Core\Permissions;

final class PermissionsTest extends TestCase
{

    public function testStudentWishlistPermissions(): void
    {
        $this->assertTrue(Permissions::can('STUDENT', 'SFx23'));
        $this->assertTrue(Permissions::can('STUDENT', 'SFx24'));
        $this->assertTrue(Permissions::can('STUDENT', 'SFx25'));
    }


    public function testStudentCannotManagePilots(): void
    {
        $this->assertFalse(Permissions::can('STUDENT', 'SFx12'));
        $this->assertFalse(Permissions::can('STUDENT', 'SFx13'));
        $this->assertFalse(Permissions::can('STUDENT', 'SFx14'));
        $this->assertFalse(Permissions::can('STUDENT', 'SFx15'));
    }


    public function testAdminHasPilotManagement(): void
    {
        $this->assertTrue(Permissions::can('ADMIN', 'SFx12'));
        $this->assertTrue(Permissions::can('ADMIN', 'SFx13'));
        $this->assertTrue(Permissions::can('ADMIN', 'SFx14'));
        $this->assertTrue(Permissions::can('ADMIN', 'SFx15'));
    }


    public function testPilotCannotCreatePilots(): void
    {
        $this->assertFalse(Permissions::can('PILOT', 'SFx13'));
    }

}