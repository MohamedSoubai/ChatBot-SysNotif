<?php

namespace Database\Factories;

use App\Models\Clients;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clients>
 */
class ClientsFactory extends Factory
{
    protected $model = Clients::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'CodeTiers'    => $faker->unique()->bothify('C###'),
            'Intitule' => $faker->company(),
            'Adresse'  => $faker->address(),
            'Telephone'=> $faker->phoneNumber(),
            'Email'    => $faker->unique()->safeEmail(),
        ];
    }
}
