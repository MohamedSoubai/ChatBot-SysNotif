<?php

namespace Database\Factories;

use App\Models\Clients;
use App\Models\Factures;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Factures>
 */
class FacturesFactory extends Factory
{
    protected $model = Factures::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

            $statuses = ['Réglé', 'Impayé', 'En attente'] ;
            $modes = ['Espèces', 'Chèque', 'Virement', 'Effet'];

            $remise   = $faker->optional(0.5)->dateTimeBetween('-30 days', 'now');
            $impaye   = $faker->optional(0.5)->dateTimeBetween('now', '+30 days');
    
            return [
                'CodeTiers'            => Clients::inRandomOrder()->first()->CodeTiers,
                'NumeroFacture'        => $faker->unique()->numerify('F###'),
                'Service'              => $faker->word(),
                'ModeReglement'        => $faker->randomElement($modes),
                'DateEntree'           => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'DateEcheance'         => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
                'DateRemise'           =>  $remise ? $remise->format('Y-m-d') : null,
                'DateImpaye'           =>  $impaye ? $impaye->format('Y-m-d') : null,
                'Reference'            => $faker->bothify('REF-#####'),
                'Libelle'              => $faker->sentence(3),
                'Banque'               => 'Banque ' . $faker->lastName,
                'MontantTotal'         => $faker->randomFloat(2, 100, 10000),
                'Statut'               => $faker->randomElement($statuses),
                'Description'          => $faker->optional()->paragraph(),
            ];
    }
}
