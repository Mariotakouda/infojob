<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->string('cv_path')->nullable()->after('note_motivation');
            $table->string('cv_nom_original')->nullable()->after('cv_path');
            $table->string('lettre_motivation_path')->nullable()->after('cv_nom_original');
            $table->string('lettre_motivation_nom_original')->nullable()->after('lettre_motivation_path');
        });
    }

    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropColumn([
                'cv_path',
                'cv_nom_original',
                'lettre_motivation_path',
                'lettre_motivation_nom_original',
            ]);
        });
    }
};
