<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CategorySeeder extends Seeder
{
    private array $categories;

    public function __construct() {
        $this->categories = $this->get();
    }

    public function run(): void
    {
        foreach ($this->categories as $key => $category) {
            Category::factory()->create([
                'name'     => $category,
                'position' => $key + 1,
                ]);
        }
    }

    public function get(): array
    {
        return [
            '衣装打扮',
            '运动用品',
            '餐厨用具',
            '文具办公',
            '家用电器',
            '数码设备',
            '母婴/幼儿',
            '玩具',
            '出门必备',
            '美妆护理',
            '食品',
            '饮品',
            '药品',
            '卫浴/个护',
            '日用家居',
            '其它',
        ];
    }
}
