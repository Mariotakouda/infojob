<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            // Identifiant officiel : numéro RCCM/NIF pour une entreprise privée,
            // ou référence de l'acte/décision de nomination pour une institution
            // publique (mairie, préfecture, ministère...).
            $table->string('numero_identification')->nullable()->after('contact_public');

            // Justificatif prouvant que la personne représente bien l'entité :
            // acte de nomination, registre de commerce, carte professionnelle...
            $table->string('document_justificatif_path')->nullable()->after('numero_identification');
            $table->string('document_justificatif_nom_original')->nullable()->after('document_justificatif_path');

            $table->enum('statut_verification', ['en_attente', 'verifiee', 'rejetee'])
                ->default('en_attente')
                ->after('document_justificatif_nom_original');

            $table->text('motif_rejet')->nullable()->after('statut_verification');
            $table->timestamp('verifiee_at')->nullable()->after('motif_rejet');
            $table->foreignId('verifiee_par')->nullable()->after('verifiee_at')
                ->constrained('users')->nullOnDelete();

            $table->index('statut_verification');
        });
    }

    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verifiee_par');
            $table->dropIndex(['statut_verification']);
            $table->dropColumn([
                'numero_identification',
                'document_justificatif_path',
                'document_justificatif_nom_original',
                'statut_verification',
                'motif_rejet',
                'verifiee_at',
            ]);
        });
    }
};
