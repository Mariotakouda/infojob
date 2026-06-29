<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    protected $signature = 'app:create-admin';

    protected $description = 'Crée un compte administrateur réel (à utiliser en production, sans données de démonstration)';

    public function handle(): int
    {
        $this->info('Création d\'un compte administrateur.');

        $name = $this->ask('Nom complet');
        $email = $this->ask('Adresse email');

        $validator = Validator::make(
            ['name' => $name, 'email' => $email],
            [
                'name'  => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $password = $this->secret('Mot de passe (au moins 8 caractères, ne s\'affiche pas à l\'écran)');
        $passwordConfirm = $this->secret('Confirmez le mot de passe');

        if ($password !== $passwordConfirm) {
            $this->error('Les mots de passe ne correspondent pas.');

            return self::FAILURE;
        }

        if (strlen($password) < 8) {
            $this->error('Le mot de passe doit contenir au moins 8 caractères.');

            return self::FAILURE;
        }

        User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
            'role'     => 'admin',
        ]);

        $this->info("Compte administrateur créé avec succès pour {$email}.");

        return self::SUCCESS;
    }
}
