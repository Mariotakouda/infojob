<?php

namespace Database\Seeders;

use App\Models\Candidature;
use App\Models\Institution;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\Procedure;
use App\Models\Requirement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministrationSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Comptes de test ───────────────────────────────────────────────
        //
        // Le mot de passe vient de SEEDER_DEFAULT_PASSWORD dans le .env.
        // Si la variable n'est pas définie, un mot de passe aléatoire est
        // généré et affiché UNE SEULE FOIS dans la console — jamais codé en dur.

        $plainPassword = env('SEEDER_DEFAULT_PASSWORD') ?: \Illuminate\Support\Str::password(14);
        $hashedPassword = Hash::make($plainPassword);

        if (! env('SEEDER_DEFAULT_PASSWORD')) {
            $this->command?->warn("Aucun SEEDER_DEFAULT_PASSWORD défini dans .env.");
            $this->command?->warn("Mot de passe généré pour les comptes de démo : {$plainPassword}");
            $this->command?->warn("Notez-le maintenant, il ne sera plus jamais affiché.");
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@travailtogo.tg'],
            [
                'name'      => 'Admin TravailTogo',
                'password'  => $hashedPassword,
                'role'      => 'admin',
                'telephone' => '+228 90 00 00 01',
            ]
        );

        $recruteur1 = User::firstOrCreate(
            ['email' => 'rh@emploi.gouv.tg'],
            [
                'name'      => 'Direction RH — Fonction Publique',
                'password'  => $hashedPassword,
                'role'      => 'recruteur',
                'telephone' => '+228 22 21 30 00',
            ]
        );

        $recruteur2 = User::firstOrCreate(
            ['email' => 'rh@togocom.tg'],
            [
                'name'      => 'RH Togocom',
                'password'  => $hashedPassword,
                'role'      => 'recruteur',
                'telephone' => '+228 22 21 60 00',
            ]
        );

        $citoyen1 = User::firstOrCreate(
            ['email' => 'kodjo.amevor@gmail.com'],
            [
                'name'      => 'Kodjo Amévor',
                'password'  => $hashedPassword,
                'role'      => 'citoyen',
                'telephone' => '+228 90 12 34 56',
            ]
        );

        $citoyen2 = User::firstOrCreate(
            ['email' => 'afia.dossou@gmail.com'],
            [
                'name'      => 'Afia Dossou',
                'password'  => $hashedPassword,
                'role'      => 'citoyen',
                'telephone' => '+228 91 23 45 67',
            ]
        );

        // ─── 2. Institutions ─────────────────────────────────────────────────

        $ministere = Institution::firstOrCreate(
            ['slug' => 'ministere-de-la-fonction-publique-abc123'],
            [
                'user_id'        => $recruteur1->id,
                'nom'            => 'Ministère de la Fonction Publique',
                'type'           => 'ministere',
                'ville'          => 'Lomé',
                'adresse'        => 'Avenue de la Marina, BP 1130, Lomé',
                'contact_public' => 'contact@fonctionpublique.gouv.tg',
                'slug'           => 'ministere-de-la-fonction-publique-abc123',
            ]
        );

        $togocom = Institution::firstOrCreate(
            ['slug' => 'togocom-sa-def456'],
            [
                'user_id'        => $recruteur2->id,
                'nom'            => 'Togocom SA',
                'type'           => 'entreprise_privee',
                'ville'          => 'Lomé',
                'adresse'        => '1 Avenue du Nouveau Marché, Lomé',
                'contact_public' => 'rh@togocom.tg',
                'slug'           => 'togocom-sa-def456',
            ]
        );

        $mairieKara = Institution::firstOrCreate(
            ['slug' => 'mairie-de-kara-ghi789'],
            [
                'user_id'        => $recruteur1->id,
                'nom'            => 'Mairie de Kara',
                'type'           => 'mairie',
                'ville'          => 'Kara',
                'adresse'        => 'Boulevard du 13 Janvier, Kara',
                'contact_public' => 'mairie@kara.tg',
                'slug'           => 'mairie-de-kara-ghi789',
            ]
        );

        // ─── 3. Procédures ───────────────────────────────────────────────────

        $proc1 = Procedure::firstOrCreate(
            ['slug' => 'inscription-concours-fonction-publique-jkl012'],
            [
                'institution_id' => $ministere->id,
                'titre'          => 'Inscription au concours de la Fonction Publique',
                'description'    => 'Procédure officielle pour s\'inscrire aux concours de recrutement dans la Fonction Publique togolaise. Ouverte aux candidats togolais âgés de 18 à 35 ans au 1er janvier de l\'année du concours.',
                'cout'           => 5000,
                'delai'          => '30 jours après clôture des dépôts',
                'lieu_depot'     => 'Direction des Ressources Humaines — Avenue de la Marina, Lomé',
                'lien_en_ligne'  => null,
                'slug'           => 'inscription-concours-fonction-publique-jkl012',
            ]
        );

        Requirement::firstOrCreate(
            ['procedure_id' => $proc1->id, 'libelle' => 'Formulaire d\'inscription dûment rempli'],
            ['est_obligatoire' => true,  'description' => 'Disponible au secrétariat ou en téléchargement']
        );
        Requirement::firstOrCreate(
            ['procedure_id' => $proc1->id, 'libelle' => 'Extrait de naissance (original)'],
            ['est_obligatoire' => true,  'description' => 'Datant de moins de 3 mois']
        );
        Requirement::firstOrCreate(
            ['procedure_id' => $proc1->id, 'libelle' => 'Certificat de nationalité togolaise'],
            ['est_obligatoire' => true,  'description' => null]
        );
        Requirement::firstOrCreate(
            ['procedure_id' => $proc1->id, 'libelle' => 'Copie certifiée du diplôme requis'],
            ['est_obligatoire' => true,  'description' => null]
        );
        Requirement::firstOrCreate(
            ['procedure_id' => $proc1->id, 'libelle' => 'Casier judiciaire vierge (bulletin n°3)'],
            ['est_obligatoire' => true,  'description' => 'Datant de moins de 3 mois']
        );
        Requirement::firstOrCreate(
            ['procedure_id' => $proc1->id, 'libelle' => 'Certificat médical d\'aptitude'],
            ['est_obligatoire' => false, 'description' => 'Requis pour certaines filières']
        );

        $proc2 = Procedure::firstOrCreate(
            ['slug' => 'demande-permis-construire-kara-mno345'],
            [
                'institution_id' => $mairieKara->id,
                'titre'          => 'Demande de permis de construire',
                'description'    => 'Démarche à effectuer avant tout début de construction ou d\'extension d\'un bâtiment sur le territoire de la commune de Kara.',
                'cout'           => 25000,
                'delai'          => '45 jours ouvrables',
                'lieu_depot'     => 'Service Urbanisme — Mairie de Kara',
                'lien_en_ligne'  => null,
                'slug'           => 'demande-permis-construire-kara-mno345',
            ]
        );

        Requirement::firstOrCreate(
            ['procedure_id' => $proc2->id, 'libelle' => 'Plan de masse (échelle 1/500)'],
            ['est_obligatoire' => true, 'description' => 'Établi par un géomètre agréé']
        );
        Requirement::firstOrCreate(
            ['procedure_id' => $proc2->id, 'libelle' => 'Titre foncier ou acte de propriété'],
            ['est_obligatoire' => true, 'description' => null]
        );
        Requirement::firstOrCreate(
            ['procedure_id' => $proc2->id, 'libelle' => 'Devis estimatif des travaux'],
            ['est_obligatoire' => true, 'description' => null]
        );
        Requirement::firstOrCreate(
            ['procedure_id' => $proc2->id, 'libelle' => 'Attestation d\'un architecte agréé'],
            ['est_obligatoire' => false, 'description' => 'Obligatoire pour les bâtiments > 2 étages']
        );

        // ─── 4. Offres d'emploi ──────────────────────────────────────────────

        $offre1 = JobOffer::firstOrCreate(
            ['titre' => 'Ingénieur Réseaux Télécoms', 'institution_id' => $togocom->id],
            [
                'institution_id'  => $togocom->id,
                'description'     => "Togocom recrute un(e) Ingénieur Réseaux Télécoms pour renforcer son équipe technique basée à Lomé.\n\nMissions principales :\n- Planification et déploiement des infrastructures 4G/5G\n- Maintenance préventive et corrective des équipements\n- Analyse des performances réseau et optimisation\n- Coordination avec les équipes terrain\n\nProfil recherché :\n- Bac+5 en Télécommunications, Réseaux ou équivalent\n- 3 ans d'expérience minimum en environnement télécom\n- Maîtrise des protocoles IP/MPLS, LTE\n- Anglais technique requis",
                'type_contrat'    => 'CDI',
                'lieu'            => 'Lomé',
                'budget_salaire'  => 450000,
                'date_expiration' => Carbon::now()->addDays(30),
                'statut'          => 'publie',
            ]
        );

        $offre2 = JobOffer::firstOrCreate(
            ['titre' => 'Chef de Projet Digital', 'institution_id' => $togocom->id],
            [
                'institution_id'  => $togocom->id,
                'description'     => "Dans le cadre de sa transformation digitale, Togocom recrute un(e) Chef de Projet Digital.\n\nMissions :\n- Piloter les projets d'innovation numérique\n- Coordination des équipes pluridisciplinaires\n- Suivi des KPIs et reporting direction\n- Animation des ateliers utilisateurs\n\nProfil :\n- Bac+5 en Gestion de projet, Informatique ou équivalent\n- Certification PMP ou équivalente appréciée\n- 2-5 ans d'expérience en gestion de projets IT",
                'type_contrat'    => 'CDD',
                'lieu'            => 'Lomé',
                'budget_salaire'  => 380000,
                'date_expiration' => Carbon::now()->addDays(20),
                'statut'          => 'publie',
            ]
        );

        $offre3 = JobOffer::firstOrCreate(
            ['titre' => 'Stagiaire Développeur Web', 'institution_id' => $togocom->id],
            [
                'institution_id'  => $togocom->id,
                'description'     => "Stage de fin d'études pour un(e) développeur(se) web motivé(e).\n\nStack : Laravel, Vue.js, MySQL\nDurée : 6 mois renouvelable\nIndemnité : selon profil",
                'type_contrat'    => 'Stage',
                'lieu'            => 'Lomé',
                'budget_salaire'  => 80000,
                'date_expiration' => Carbon::now()->addDays(45),
                'statut'          => 'publie',
            ]
        );

        $offre4 = JobOffer::firstOrCreate(
            ['titre' => 'Maçon qualifié — Construction École Primaire', 'institution_id' => $mairieKara->id],
            [
                'institution_id'  => $mairieKara->id,
                'description'     => "La Mairie de Kara lance un appel à candidatures pour des maçons qualifiés dans le cadre de la construction d'une école primaire à Kara-Est.\n\nMissions : maçonnerie, pose de dalles, finitions\nDurée : 4 mois de chantier\nLogement de chantier fourni",
                'type_contrat'    => 'Prestation_Artisanale',
                'lieu'            => 'Kara',
                'budget_salaire'  => 150000,
                'date_expiration' => Carbon::now()->addDays(15),
                'statut'          => 'publie',
            ]
        );

        JobOffer::firstOrCreate(
            ['titre' => 'Assistant Administratif', 'institution_id' => $ministere->id],
            [
                'institution_id'  => $ministere->id,
                'description'     => "Recrutement d'un(e) assistant(e) administratif(ve) pour la Direction des Ressources Humaines.",
                'type_contrat'    => 'CDI',
                'lieu'            => 'Lomé',
                'budget_salaire'  => 180000,
                'date_expiration' => Carbon::now()->addDays(60),
                'statut'          => 'en_attente',
            ]
        );

        // ─── 5. Profils artisan / citoyen ────────────────────────────────────

        $profil1 = JobApplication::firstOrCreate(
            ['user_id' => $citoyen1->id],
            [
                'titre_profil'     => 'Développeur Web Full-Stack Laravel / Vue.js',
                'secteur_activite' => 'Informatique & Numérique',
                'competences'      => 'Laravel, Vue.js, PHP, MySQL, TailwindCSS, API REST, Git, Docker',
                'disponibilite'    => true,
                'ville'            => 'Lomé',
                'statut_moderation'=> 'approuve',
            ]
        );

        $profil2 = JobApplication::firstOrCreate(
            ['user_id' => $citoyen2->id],
            [
                'titre_profil'     => 'Maçon — Finisseur — Carreleur',
                'secteur_activite' => 'BTP & Artisanat',
                'competences'      => 'Maçonnerie, coffrage, ferraillage, pose de carrelage, enduit, peinture',
                'disponibilite'    => true,
                'ville'            => 'Kara',
                'statut_moderation'=> 'approuve',
            ]
        );

        // ─── 6. Candidatures ─────────────────────────────────────────────────

        Candidature::firstOrCreate(
            ['user_id' => $citoyen1->id, 'job_offer_id' => $offre1->id],
            [
                'note_motivation'    => 'Passionné par les télécoms, j\'ai 4 ans d\'expérience en déploiement réseau LTE. Je souhaite mettre mes compétences au service de Togocom.',
                'statut_candidature' => 'en_discussion',
            ]
        );

        Candidature::firstOrCreate(
            ['user_id' => $citoyen1->id, 'job_offer_id' => $offre3->id],
            [
                'note_motivation'    => 'Développeur Laravel depuis 3 ans, je suis disponible immédiatement.',
                'statut_candidature' => 'acceptee',
            ]
        );

        Candidature::firstOrCreate(
            ['user_id' => $citoyen2->id, 'job_offer_id' => $offre4->id],
            [
                'note_motivation'    => 'Maçon qualifié avec 8 ans d\'expérience dans la construction en Afrique de l\'Ouest.',
                'statut_candidature' => 'recue',
            ]
        );

        $this->command->info('Seeder terminé — comptes de test :');
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['Admin',     'admin@travailtogo.tg',   $plainPassword],
                ['Recruteur', 'rh@emploi.gouv.tg',      $plainPassword],
                ['Recruteur', 'rh@togocom.tg',          $plainPassword],
                ['Citoyen',   'kodjo.amevor@gmail.com', $plainPassword],
                ['Citoyen',   'afia.dossou@gmail.com',  $plainPassword],
            ]
        );
    }
}
