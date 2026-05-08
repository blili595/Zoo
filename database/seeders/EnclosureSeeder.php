<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Enclosure;
use App\Models\Animal;
use App\Models\User;
use Database\Factories\AnimalFactory;
use Database\Factories\EnclosureFactory;
use Database\Factories\UserFactory;
use App\Helper\Helper;


class EnclosureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $enclosures = Enclosure::factory()->count(5)->create();
        $users = User::all();
        foreach ($enclosures as $enclosure) {
            $assignedUsers = $users->random(rand(1, 5));
            $enclosure->users()->attach($assignedUsers);
        }
    }
}
