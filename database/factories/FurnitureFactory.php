<?php

namespace Database\Factories;

use App\Models\Furniture;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FurnitureFactory extends Factory
{
    protected $model = Furniture::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->name(),
            'image'       => $this->faker->word(),
            'description' => $this->faker->text(),
            'position'    => $this->faker->randomNumber(),
            'is_private'  => $this->faker->boolean(),
            'room_id'     => $this->faker->randomNumber(),
            'user_id'     => 1,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ];
    }
}
