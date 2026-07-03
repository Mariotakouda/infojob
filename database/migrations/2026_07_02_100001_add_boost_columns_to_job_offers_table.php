<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_offers', function (Blueprint $table) {
            $table->boolean('est_boostee')->default(false)->after('statut');
            $table->timestamp('boost_expire_at')->nullable()->after('est_boostee');
        });
    }

    public function down(): void
    {
        Schema::table('job_offers', function (Blueprint $table) {
            $table->dropColumn(['est_boostee', 'boost_expire_at']);
        });
    }
};
