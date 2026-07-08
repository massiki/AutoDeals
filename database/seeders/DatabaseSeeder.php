<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin AutoDeals',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'User AutoDeals',
            'email' => 'user@gmail.com',
            'role' => 'user',
        ]);

        $cars = Car::factory(30)->create();

        Inquiry::factory(15)->recycle($cars)->create();
    }
}
