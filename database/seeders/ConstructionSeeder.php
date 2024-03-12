<?php

namespace Database\Seeders;

use App\Models\Construction;
use Illuminate\Database\Seeder;

class ConstructionSeeder extends Seeder
{
    private array $constructions;

    public function __construct() {
        $this->constructions = $this->get();
    }

    public function run(): void
    {
        foreach ($this->constructions as $construction) {
            Construction::factory()->create($construction);
        }
    }

    public function get(): array
    {
        return [
            [
                'name'        => '出租屋',
                'image'       => null,
                'location'    => 'XX省-XX市-XX区-XX',
                'description' => 'XXXXXXXXX 出租屋',
                'position'    => 1,
                'user_id'     => 1,
            ],

            [
                'name'        => '学校',
                'image'       => null,
                'location'    => 'XX省-XX市-XX区-XX',
                'description' => 'XXXXXXXXX 学校',
                'position'    => 2,
                'user_id'     => 1,
            ],

            [
                'name'        => '公司',
                'image'       => null,
                'location'    => 'XX省-XX市-XX区-XX',
                'description' => 'XXXXXXXXX 公司',
                'position'    => 3,
                'user_id'     => 1,
            ],

            [
                'name'        => '老家',
                'image'       => null,
                'location'    => 'XX省-XX市-XX区-XX',
                'description' => 'XXXXXXXXX 老家',
                'position'    => 4,
                'user_id'     => 1,
            ],
        ];
    }
}
