<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaderboardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('name');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('point');
            $table->unsignedBigInteger('level');
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaderboards');
    }
}
