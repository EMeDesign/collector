<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Keyword;
use App\Models\User;
use App\Models\UserKeyword;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserKeywordFactory extends Factory
{
    protected $model = UserKeyword::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'keyword_id' => Keyword::factory(),
            'item_id' => Item::factory(),
        ];
    }
}
