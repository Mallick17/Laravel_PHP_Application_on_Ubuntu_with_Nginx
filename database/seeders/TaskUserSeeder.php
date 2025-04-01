<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory(20)->create(); // storing all collection of user generated here
        $task = Task::factory(10)->create(); //same the above

        foreach ($task as $t) {
            $t->users()->attach(
                $user->random(2)->pluck('id'),
                ['status' => fake()->randomElement(['pending', 'in-progress', 'completed'])]
            );
        }
    }
}
