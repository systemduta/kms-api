<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasDimensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pas_dimensis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('3p_id');
            $table->string('name');
            $table->timestamps();
            
            $table->foreign('3p_id')->references('id')->on('pas_3p');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pas_dimensis');
    }
}
