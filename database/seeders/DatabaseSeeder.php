<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Enclosure;
use App\Models\Animal;
use Database\Factories\AnimalFactory;
use Database\Factories\EnclosureFactory;
use Database\Factories\UserFactory;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        $this->call([
            EnclosureSeeder::class
        ]);
        $user = User::find(1);
        $user->name = 'Admin';
        $user->email = 'admin@admin.com';
        $user->admin = true;
        $user->save();
         
        
        
    
    }
}
