<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nom');
            $table->enum('type', [
                'ministere',
                'mairie',
                'prefecture',
                'direction',
                'presidence',
                'entreprise_privee',
                'particulier',
            ]);
            $table->string('ville');
            $table->string('adresse');
            $table->string('contact_public')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();

            $table->index('ville');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
