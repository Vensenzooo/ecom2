<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'auteur' => $this->faker->name,
            'categorie_id' => Category::factory(),
            'niveau_expertise' => $this->faker->randomElement(['Débutant', 'Intermédiaire', 'Avancé']),
            'stock' => $this->faker->numberBetween(0, 100),
            'prix' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
