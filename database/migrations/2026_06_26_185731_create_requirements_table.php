<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procedure_id')->constrained('procedures')->cascadeOnDelete();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->boolean('est_obligatoire')->default(true);
            $table->timestamps();

            $table->index('procedure_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requirements');
    }
};
