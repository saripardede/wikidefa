<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tutorials', function (Blueprint $table) {
            $table->text('catatan_revisi')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tutorials', function (Blueprint $table) {
            $table->dropColumn('catatan_revisi');
        });
    }
    
};
