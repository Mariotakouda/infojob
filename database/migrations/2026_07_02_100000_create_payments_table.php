<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Relation polymorphe : permet de payer pour booster une offre
            // aujourd'hui, et demain un abonnement, une mise en avant de
            // profil artisan, etc. sans nouvelle table.
            $table->nullableMorphs('payable');

            $table->string('identifier')->unique()->comment('Notre référence interne, envoyée à PayGate');
            $table->string('tx_reference')->nullable()->comment('Référence PayGate Global');

            $table->unsignedInteger('montant')->comment('Montant en F CFA');
            $table->enum('moyen_paiement', ['FLOOZ', 'TMONEY'])->nullable();
            $table->string('telephone')->nullable();

            $table->enum('statut', ['en_attente', 'reussi', 'echoue', 'expire', 'annule'])
                  ->default('en_attente');

            $table->string('motif')->nullable()->comment('Ex: Mise en avant offre #8');
            $table->timestamp('paye_a')->nullable();

            $table->timestamps();

            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
