<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\MiniProjectTag;
use App\Models\Service;
use App\Models\TechStack;
use App\Models\User;
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

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);


        Work::factory(10)->create();
        Service::factory(4)->create();
        TechStack::factory(10)->create();
        WorkTechStack::factory(10)->create();
        MiniProjectTag::factory(4)->create();
    }
}
