<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        for ($i = 0; $i < 10; $i++) {
            Task::factory()->create([
                'creator_id' => $users->random()->id,
                'assigned_user_id' => $users->random()->id,
            ]);
        }
    }
}
