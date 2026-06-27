<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->string('titre');
            $table->text('description');
            $table->enum('type_contrat', ['CDI', 'CDD', 'Stage', 'Prestation_Artisanale']);
            $table->string('lieu');
            $table->unsignedBigInteger('budget_salaire')->nullable()->comment('Rémunération en F CFA');
            $table->date('date_expiration');
            $table->enum('statut', ['en_attente', 'publie', 'expire'])->default('en_attente');
            $table->timestamps();

            $table->index('statut');
            $table->index('date_expiration');
            $table->index('type_contrat');
            $table->index('institution_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
