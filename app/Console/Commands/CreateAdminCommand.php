<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    protected $signature = 'app:create-admin
        {--name= : Nom complet (mode non-interactif)}
        {--email= : Adresse email (mode non-interactif)}
        {--password= : Mot de passe (mode non-interactif, au moins 8 caractères)}';

    protected $description = 'Crée un compte administrateur réel (à utiliser en production, sans données de démonstration)';

    public function handle(): int
    {
        // Mode non-interactif : utilisé au démarrage du container (Render free
        // tier n'a pas de Shell/One-Off Jobs), déclenché via variables d'env.
        if ($this->option('email') && $this->option('password')) {
            return $this->handleNonInteractive();
        }

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

    private function handleNonInteractive(): int
    {
        $name = $this->option('name') ?: 'Administrateur';
        $email = $this->option('email');
        $password = $this->option('password');

        $validator = Validator::make(
            ['name' => $name, 'email' => $email, 'password' => $password],
            [
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8'],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->warn("Un compte existe déjà avec l'email {$email} — aucune action effectuée.");

            return self::SUCCESS;
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