<?php

namespace Database\Factories;

use App\Models\Construction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ConstructionFactory extends Factory
{
    protected $model = Construction::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->name(),
            'image'       => null,
            'location'    => $this->faker->word(),
            'description' => $this->faker->text(),
            'position'    => $this->faker->randomNumber(),
            'user_id'     => 1,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ];
    }
}
