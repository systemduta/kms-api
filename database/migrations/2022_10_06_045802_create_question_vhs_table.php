<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionVhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_vhs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('materi_id');
            $table->string('question');
            $table->timestamps();

            
            $table->foreign('materi_id')->references('id')->on('materi_vhs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_vhs');
    }
}
