<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'            => $this->faker->name(),
            'image'           => null,
            'description'     => $this->faker->text(),
            'quantity'        => $this->faker->randomNumber(),
            'furniture_id'    => 1,
            'category_id'     => 0,
            'unit_id'         => 1,
            'user_id'         => 1,
            'owner_id'        => 1,
            'obtained_at'     => Carbon::now(),
            'expired_at'      => Carbon::now()->addDays($this->faker->randomNumber(2)),
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        ];
    }
}
