<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    private array $rooms;

    public function __construct() {
        $this->rooms = $this->get();
    }

    public function run(): void
    {
        foreach ($this->rooms as $room) {
            Room::factory()->create($room);
        }
    }

    public function get(): array
    {
        return [
            [
                'name'        => '玄关',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 玄关',
                'position'    => 1,
                'user_id'     => 1,
            ],

            [
                'name'        => '客厅',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 客厅',
                'position'    => 2,
                'user_id'     => 1,
            ],

            [
                'name'        => '主卧',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 主卧 (父母住)',
                'position'    => 3,
                'user_id'     => 1,
            ],

            [
                'name'        => '次卧 (A)',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 次卧 (祖父母住)',
                'position'    => 4,
                'user_id'     => 1,
            ],

            [
                'name'        => '次卧 (B)',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 次卧 (自己住)',
                'position'    => 5,
                'user_id'     => 1,
            ],

            [
                'name'        => '餐厅',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 餐厅',
                'position'    => 6,
                'user_id'     => 1,
            ],

            [
                'name'        => '厨房',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 厨房',
                'position'    => 7,
                'user_id'     => 1,
            ],

            [
                'name'        => '主卫',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 主卫',
                'position'    => 8,
                'user_id'     => 1,
            ],

            [
                'name'        => '次卫',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 次卫',
                'position'    => 9,
                'user_id'     => 1,
            ],

            [
                'name'        => '书房',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 书房',
                'position'    => 10,
                'user_id'     => 1,
            ],

            [
                'name'        => '储物间',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 储物间',
                'position'    => 11,
                'user_id'     => 1,
            ],

            [
                'name'        => '阳台',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 阳台',
                'position'    => 12,
                'user_id'     => 1,
            ],
        ];
    }
}
