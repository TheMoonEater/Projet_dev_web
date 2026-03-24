<?php
declare(strict_types=1);

namespace App\Core;

final class Permissions
{
    /** @var array<string, array<string, bool>> */
    private const MAP = [
        'ADMIN' => [
            'SFx1' => true,  'SFx2' => true,  'SFx3' => true,  'SFx4' => true,  'SFx5' => true,  'SFx6' => true,
            'SFx7' => true,  'SFx8' => true,  'SFx9' => true,  'SFx10' => true, 'SFx11' => true,
            'SFx12' => true, 'SFx13' => true, 'SFx14' => true, 'SFx15' => true,
            'SFx16' => true, 'SFx17' => true, 'SFx18' => true, 'SFx19' => true,
            'SFx20' => false,'SFx21' => false,'SFx22' => false,
            'SFx23' => false,'SFx24' => false,'SFx25' => false,
        ],
        'PILOT' => [
            'SFx1' => true,  'SFx2' => true,  'SFx3' => true,  'SFx4' => true,  'SFx5' => true,  'SFx6' => true,
            'SFx7' => true,  'SFx8' => true,  'SFx9' => true,  'SFx10' => true, 'SFx11' => true,
            'SFx12' => false,'SFx13' => false,'SFx14' => false,'SFx15' => false,
            'SFx16' => true, 'SFx17' => true, 'SFx18' => true, 'SFx19' => true,
            'SFx20' => false,'SFx21' => false,'SFx22' => true,
            'SFx23' => false,'SFx24' => false,'SFx25' => false,
        ],
        'STUDENT' => [
            'SFx1' => true,  'SFx2' => true,  'SFx3' => false, 'SFx4' => false, 'SFx5' => false, 'SFx6' => false,
            'SFx7' => true,  'SFx8' => false, 'SFx9' => false, 'SFx10' => false,'SFx11' => true,
            'SFx12' => false,'SFx13' => false,'SFx14' => false,'SFx15' => false,
            'SFx16' => false,'SFx17' => false,'SFx18' => false,'SFx19' => false,
            'SFx20' => true, 'SFx21' => true, 'SFx22' => false,
            'SFx23' => true, 'SFx24' => true, 'SFx25' => true,
        ],
        'ANON' => [
            'SFx1' => true,  'SFx2' => true,  'SFx7' => true,  'SFx11' => true,
        ],
    ];

    public static function can(string $role, string $sfxCode): bool
    {
        $role = strtoupper($role);
        if (!isset(self::MAP[$role])) $role = 'ANON';

        return self::MAP[$role][$sfxCode] ?? false;
    }
}