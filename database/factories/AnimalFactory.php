<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Enclosure;
use App\Models\Animal;
use App\Models\User;
use Database\Factories\AnimalFactory;
use Database\Factories\EnclosureFactory;
use Database\Factories\UserFactory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'species' =>  null, 
            'is_predator' => null,
            'born_at' => $this->faker->dateTime(),
            'enclosure_id' => null,
            'picture' => null
        ];
    }
}
