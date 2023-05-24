<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasPenilaianAbsensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pas_penilaian_absens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('dimensi_id');
            $table->date('date');
            $table->integer('nilai');
            $table->integer('max_nilai')->default(4);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('dimensi_id')->references('id')->on('pas_dimensis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pas_penilaian_absens');
    }
}
