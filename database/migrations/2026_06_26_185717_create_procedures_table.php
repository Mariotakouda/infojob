<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->string('titre');
            $table->text('description');
            $table->unsignedInteger('cout')->default(0)->comment('Montant en Francs CFA');
            $table->string('delai')->nullable()->comment('Ex: 48 heures, 2 semaines');
            $table->string('lieu_depot')->nullable();
            $table->string('lien_en_ligne')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();

            $table->index('institution_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedures');
    }
};
