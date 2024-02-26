<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
         User::factory()->create([
             'name'  => config(key: 'collector.owner.username', default: 'Owner'),
             'password' => Hash::make(config(key: 'collector.owner.password', default: 'DB+vSw3SFUJI52*U')),
             'email' => config(key: 'collector.owner.email', default: 'owner@example.com'),
         ]);
    }
}
