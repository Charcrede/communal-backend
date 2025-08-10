<?php

namespace Database\Seeders;

use App\Models\Rubric;
use Illuminate\Database\Seeder;

class RubricSeeder extends Seeder
{
    public function run(): void
    {
        $rubrics = [
            [
                'name' => 'La voix du maire',
                'slug' => 'maire',
                'description' => 'Informations officielles et messages adressés à la population par le maire, incluant les projets en cours et les décisions municipales.',
            ],
            [
                'name' => 'La voix du conseil communal',
                'slug' => 'conseil',
                'description' => 'Comptes rendus des réunions du conseil communal, résolutions adoptées et orientations stratégiques pour la commune.',
            ],
            [
                'name' => 'La voix du conseiller local',
                'slug' => 'conseiller',
                'description' => 'Initiatives et actions menées par les conseillers locaux, ainsi que leurs interventions auprès des citoyens.',
            ],
            [
                'name' => 'Publi-reportage',
                'slug' => 'publi',
                'description' => 'Articles promotionnels et reportages sponsorisés mettant en valeur des entreprises, événements ou projets locaux.',
            ],
        ];

        foreach ($rubrics as $rubric) {
            Rubric::create($rubric);
        }
    }
}
