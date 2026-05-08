<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Enclosure;
use App\Models\Animal;
use App\Models\User;
use Database\Factories\AnimalFactory;
use Database\Factories\EnclosureFactory;
use Database\Factories\UserFactory;
use App\Helper\Helper;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Encosure>
 */
class EnclosureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'limit' => $this->faker->numberBetween(1, 15),
            'feeding_at' => $this->faker->time(),
            'is_predator' => $this->faker->boolean()
        ];
    }

    public function configure()
    {
        /*return $this->afterCreating(function (Enclosure $enclosure) {
            // Create animals in the enclosure
            $species = Helper::all();
            $sameSpecies = collect($species)->filter(fn($isPredator) => $isPredator === $enclosure->is_predator)->keys()->toArray();
            });
            Animal::factory()->count($enclosure->limit)->create([
                'species' => $sameSpecies[array_rand($sameSpecies)],
                'is_predator' => $enclosure->is_predator,
                'enclosure_id' => $enclosure->id,
            ]);*/
            return $this->afterCreating(function (Enclosure $enclosure) {
                
                \Log::info("Creating animals for enclosure ID: {$enclosure->id}");
        
                
                $species = Helper::all();
                $sameSpecies = collect($species)->filter(fn($isPredator) => $isPredator === $enclosure->is_predator)->keys()->toArray();
                
                if (!empty($sameSpecies)) {
                    \Log::info("Creating {$enclosure->limit} animals for this enclosure.");
        
                    Animal::factory()->count($enclosure->limit)->create([
                        'species' => $sameSpecies[array_rand($sameSpecies)],
                        'is_predator' => $enclosure->is_predator,
                        'enclosure_id' => $enclosure->id,
                    ]);
                } else {
                    \Log::info("No species match the predator status for this enclosure.");
                }
            });
        
    }
}
