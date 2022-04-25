<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLamcrossesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lamcrosses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('crossfunction_id');
            $table->string('name');
            $table->string('file');
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('crossfunction_id')->references('id')->on('crossfunctions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lamcrosses');
    }
}
