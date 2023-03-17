<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsBasicToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('isBasic')->after('role')->nullable();
            $table->string('isClass')->after('isBasic')->nullable();
            $table->string('isCamp')->after('isClass')->nullable();
            $table->string('isAcademy')->after('isCamp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwal_user_vhs', function (Blueprint $table) {
            //
        });
    }
}
