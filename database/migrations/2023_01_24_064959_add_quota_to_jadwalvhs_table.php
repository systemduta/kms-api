<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuotaToJadwalvhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jadwalvhs', function (Blueprint $table) {
            $table->string('type')->after('batch')->nullable();
            $table->string('isCity')->after('end')->nullable();
            $table->string('quota')->after('isCity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwalvhs', function (Blueprint $table) {
            //
        });
    }
}
