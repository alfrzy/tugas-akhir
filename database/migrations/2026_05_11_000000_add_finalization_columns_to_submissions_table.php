<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
        $table->boolean('is_finalized')->default(false)->after('finished_at');
        $table->boolean('auto_submitted')->default(false)->after('is_finalized');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('submissions', function (Blueprint $table) {
        $table->dropColumn(['is_finalized', 'auto_submitted']);
    });

    }
};
