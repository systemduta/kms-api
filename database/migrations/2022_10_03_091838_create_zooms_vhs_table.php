<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomsVhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zooms_vhs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_id');
            $table->text('name');
            $table->dateTime('times');
            $table->text('link');
            $table->text('meeting_id');
            $table->text('password');
            $table->timestamps();

            $table->foreign('jadwal_id')->references('id')->on('jadwalvhs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zooms_vhs');
    }
}
