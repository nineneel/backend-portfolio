<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Service;
use App\Models\TechStack;
use App\Models\Work;
use App\Models\WorkTechStack;
use Database\Factories\WorkFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        Work::factory(10)->create();
        Service::factory(10)->create();
        TechStack::factory(10)->create();
        WorkTechStack::factory(10)->create();
    }
}
