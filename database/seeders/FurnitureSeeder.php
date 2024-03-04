<?php

namespace Database\Seeders;

use App\Models\Furniture;
use Illuminate\Database\Seeder;

class FurnitureSeeder extends Seeder
{
    private array $furniture;

    public function __construct() {
        $this->furniture = $this->get();
    }

    public function run(): void
    {
        foreach ($this->furniture as $value) {
            Furniture::factory()->create($value);
        }
    }

    public function get(): array
    {
        return [
            [
                'name'        => '鞋柜',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 玄关 鞋柜',
                'position'    => 1,
                'is_private'  => false,
                'room_id'     => 1,
                'user_id'     => 1,
            ],

            [
                'name'        => '沙发',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 客厅 沙发',
                'position'    => 2,
                'is_private'  => false,
                'room_id'     => 2,
                'user_id'     => 1,
            ],

            [
                'name'        => '衣柜',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 主卧 (父母住) 衣柜',
                'position'    => 3,
                'is_private'  => false,
                'room_id'     => 3,
                'user_id'     => 1,
            ],

            [
                'name'        => '双人床',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 次卧 (祖父母住) 双人床',
                'position'    => 4,
                'is_private'  => false,
                'room_id'     => 4,
                'user_id'     => 1,
            ],

            [
                'name'        => '床头柜',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 次卧 (自己住) 床头柜',
                'position'    => 5,
                'is_private'  => false,
                'room_id'     => 5,
                'user_id'     => 1,
            ],

            [
                'name'        => '餐桌',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 餐厅 餐桌',
                'position'    => 6,
                'is_private'  => false,
                'room_id'     => 6,
                'user_id'     => 1,
            ],

            [
                'name'        => '橱柜',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 厨房 橱柜',
                'position'    => 7,
                'is_private'  => false,
                'room_id'     => 7,
                'user_id'     => 1,
            ],

            [
                'name'        => '浴室柜',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 主卫 浴室柜',
                'position'    => 8,
                'is_private'  => false,
                'room_id'     => 8,
                'user_id'     => 1,
            ],

            [
                'name'        => '置物架',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 次卫 置物架',
                'position'    => 9,
                'is_private'  => false,
                'room_id'     => 9,
                'user_id'     => 1,
            ],

            [
                'name'        => '书桌',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 书房 书桌',
                'position'    => 10,
                'is_private'  => false,
                'room_id'     => 10,
                'user_id'     => 1,
            ],

            [
                'name'        => '储物柜',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 储物间 储物柜',
                'position'    => 11,
                'is_private'  => false,
                'room_id'     => 11,
                'user_id'     => 1,
            ],

            [
                'name'        => '杂货架',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 阳台 杂货架',
                'position'    => 12,
                'is_private'  => false,
                'room_id'     => 12,
                'user_id'     => 1,
            ],

            [
                'name'        => '电脑桌',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 书房 电脑桌',
                'position'    => 13,
                'is_private'  => false,
                'room_id'     => 10,
                'user_id'     => 1,
            ],
        ];
    }
}
