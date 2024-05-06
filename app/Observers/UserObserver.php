<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Create Default Constructions For New Registered User
        $constructionArr = $this->getDefaultConstructions();
        $constructionCollection = $user->constructions()->createMany($constructionArr);

        //Create Default Rooms For New Registered User
        $home = $constructionCollection->where('name', '老家')->first();
        $roomArr = $this->getDefaultRooms($home->id);
        $roomCollection = $user->rooms()->createMany($roomArr);

        //Create Default Furniture For New Registered User
        $furnitureArr = [];
        foreach ($roomCollection as $room) {
            $furnitureArr[] = $this->getDefaultFurniture($room->name, $room->id);
        }

        $user->furniture()->createMany($furnitureArr);

    }

    /**
     * @return array
     */
    private function getDefaultConstructions(): array
    {
        return [
            [
                'name'        => '出租屋',
                'image'       => null,
                'location'    => 'XX省-XX市-XX区-XX',
                'description' => 'XXXXXXXXX 出租屋',
                'position'    => 1,
            ],

            [
                'name'        => '学校',
                'image'       => null,
                'location'    => 'XX省-XX市-XX区-XX',
                'description' => 'XXXXXXXXX 学校',
                'position'    => 2,
            ],

            [
                'name'        => '公司',
                'image'       => null,
                'location'    => 'XX省-XX市-XX区-XX',
                'description' => 'XXXXXXXXX 公司',
                'position'    => 3,
            ],

            [
                'name'        => '老家',
                'image'       => null,
                'location'    => 'XX省-XX市-XX区-XX',
                'description' => 'XXXXXXXXX 老家',
                'position'    => 4,
            ],
        ];
    }

    /**
     * @param int $constructionId
     *
     * @return array[]
     */
    private function getDefaultRooms(int $constructionId): array
    {
        return [
            [
                'name'            => '玄关',
                'image'           => null,
                'description'     => 'XXX 小区 XXX 单元 XXX 玄关',
                'position'        => 1,
                'construction_id' => $constructionId,
            ],

            [
                'name'            => '客厅',
                'image'           => null,
                'description'     => 'XXX 小区 XXX 单元 XXX 客厅',
                'position'        => 2,
                'construction_id' => $constructionId,
            ],

            [
                'name'            => '主卧',
                'image'           => null,
                'description'     => 'XXX 小区 XXX 单元 XXX 主卧 (父母住)',
                'position'        => 3,
                'construction_id' => $constructionId,
            ],

            [
                'name'            => '次卧 (A)',
                'image'           => null,
                'description'     => 'XXX 小区 XXX 单元 XXX 次卧 (祖父母住)',
                'position'        => 4,
                'construction_id' => $constructionId,
            ],

            [
                'name'            => '次卧 (B)',
                'image'           => null,
                'description'     => 'XXX 小区 XXX 单元 XXX 次卧 (自己住)',
                'position'        => 5,
                'construction_id' => $constructionId,
            ],

            [
                'name'            => '餐厅',
                'image'           => null,
                'description'     => 'XXX 小区 XXX 单元 XXX 餐厅',
                'position'        => 6,
                'construction_id' => $constructionId,
            ],

            [
                'name'            => '厨房',
                'image'           => null,
                'description'     => 'XXX 小区 XXX 单元 XXX 厨房',
                'position'        => 7,
                'construction_id' => $constructionId,
            ],

            [
                'name'            => '主卫',
                'image'           => null,
                'description'     => 'XXX 小区 XXX 单元 XXX 主卫',
                'position'        => 8,
                'construction_id' => $constructionId,
            ],

            [
                'name'            => '次卫',
                'image'           => null,
                'description'     => 'XXX 小区 XXX 单元 XXX 次卫',
                'position'        => 9,
                'construction_id' => $constructionId,
            ],

            [
                'name'            => '书房',
                'image'           => null,
                'description'     => 'XXX 小区 XXX 单元 XXX 书房',
                'position'        => 10,
                'construction_id' => $constructionId,
            ],

            [
                'name'            => '储物间',
                'image'           => null,
                'description'     => 'XXX 小区 XXX 单元 XXX 储物间',
                'position'        => 11,
                'construction_id' => $constructionId,
            ],

            [
                'name'            => '阳台',
                'image'           => null,
                'description'     => 'XXX 小区 XXX 单元 XXX 阳台',
                'position'        => 12,
                'construction_id' => $constructionId,
            ],
        ];
    }

    /**
     * @param string $roomName
     * @param int $roomId
     *
     * @return array
     */
    private function getDefaultFurniture(string $roomName, int $roomId): array
    {
        return match ($roomName) {
            '玄关' => [
                'name'        => '鞋柜',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 玄关 鞋柜',
                'position'    => 1,
                'is_private'  => false,
                'room_id'     => $roomId,
            ],

            '客厅' => [
                'name'        => '沙发',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 客厅 沙发',
                'position'    => 2,
                'is_private'  => false,
                'room_id'     => $roomId,
            ],

            '主卧' => [
                'name'        => '衣柜',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 主卧 (父母住) 衣柜',
                'position'    => 3,
                'is_private'  => false,
                'room_id'     => $roomId,
            ],

            '次卧 (A)' => [
                'name'        => '双人床',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 次卧 (祖父母住) 双人床',
                'position'    => 4,
                'is_private'  => false,
                'room_id'     => $roomId,
            ],

            '次卧 (B)' => [
                'name'        => '床头柜',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 次卧 (自己住) 床头柜',
                'position'    => 5,
                'is_private'  => false,
                'room_id'     => $roomId,
            ],

            '餐厅' => [
                'name'        => '餐桌',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 餐厅 餐桌',
                'position'    => 6,
                'is_private'  => false,
                'room_id'     => $roomId,
            ],

            '厨房' => [
                'name'        => '橱柜',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 厨房 橱柜',
                'position'    => 7,
                'is_private'  => false,
                'room_id'     => $roomId,
            ],

            '主卫' => [
                'name'        => '浴室柜',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 主卫 浴室柜',
                'position'    => 8,
                'is_private'  => false,
                'room_id'     => $roomId,
            ],

            '次卫' => [
                'name'        => '置物架',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 次卫 置物架',
                'position'    => 9,
                'is_private'  => false,
                'room_id'     => $roomId,
            ],

            '书房' => [
                'name'        => '书桌',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 书房 书桌',
                'position'    => 10,
                'is_private'  => false,
                'room_id'     => $roomId,
            ],

            '储物间' => [
                'name'        => '储物柜',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 储物间 储物柜',
                'position'    => 11,
                'is_private'  => false,
                'room_id'     => $roomId,
            ],

            '阳台' => [
                'name'        => '杂货架',
                'image'       => null,
                'description' => 'XXX 小区 XXX 单元 XXX 阳台 杂货架',
                'position'    => 12,
                'is_private'  => false,
                'room_id'     => $roomId,
            ],
        };
    }
}
