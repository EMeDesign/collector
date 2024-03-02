<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->text(),
            'position' => $this->faker->biasedNumberBetween(min: 0, max: 100),
        ];
    }
}
