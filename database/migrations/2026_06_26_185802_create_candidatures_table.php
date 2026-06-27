<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('job_offer_id')->constrained('job_offers')->cascadeOnDelete();
            $table->text('note_motivation')->nullable();
            $table->enum('statut_candidature', ['recue', 'en_discussion', 'acceptee', 'refusee'])
                  ->default('recue');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Contrainte critique : un user ne postule qu'une seule fois par offre
            $table->unique(['user_id', 'job_offer_id']);

            $table->index('statut_candidature');
            $table->index('job_offer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
