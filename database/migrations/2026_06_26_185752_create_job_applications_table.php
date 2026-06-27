<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('titre_profil');
            $table->string('secteur_activite');
            $table->string('cv_ou_portfolio_path')->nullable();
            $table->text('competences');
            $table->boolean('disponibilite')->default(true);
            $table->string('ville');
            $table->enum('statut_moderation', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            $table->timestamps();

            $table->index('statut_moderation');
            $table->index('ville');
            $table->index('disponibilite');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
