<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    private array $units;

    public function __construct() {
        $this->units = $this->get();
    }

    public function run(): void
    {
        foreach ($this->units as $unit) {
            Unit::factory()->create(['name' => $unit]);
        }
    }

    public function get(): array
    {
        return [
            '个',
            '块',
            '条',
            '只',
            '本',
            '件',
            '张',
            '双',
            '杯',
            '片',
            '套',
            '层',
            '支',
            '颗',
            '组',
            '幅',
            '台',
            '盒',
            '盘',
            '包',
            '卷',
            '箱',
            '粒',
        ];
    }
}
