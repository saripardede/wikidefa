<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            if (!Schema::hasColumn('rewards', 'tutorial_id')) {
                $table->unsignedBigInteger('tutorial_id')->nullable()->after('user_id');
                $table->foreign('tutorial_id')->references('id')->on('tutorials')->onDelete('set null');
            }
        });
    }


    public function down(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->dropForeign(['tutorial_id']);
            $table->dropColumn('tutorial_id');
        });
    }
};
