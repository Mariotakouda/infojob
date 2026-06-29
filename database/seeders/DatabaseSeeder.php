<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Garde-fou : ce seeder crée des comptes et données de DÉMONSTRATION.
        // Il ne doit jamais s'exécuter sur un site en ligne.
        if ($this->command && app()->environment('production')) {
            $this->command->error('Seeding annulé : APP_ENV=production.');
            $this->command->error('Ce seeder contient des comptes de démonstration et ne doit pas tourner en production.');

            return;
        }

        $this->call([
            AdministrationSeeder::class,
        ]);
    }
}
