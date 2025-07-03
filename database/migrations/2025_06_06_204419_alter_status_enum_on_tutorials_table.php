<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStatusEnumOnTutorialsTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE tutorials MODIFY status ENUM('pending','approved','rejected','revision') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE tutorials MODIFY status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
    }
}

