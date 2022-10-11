<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMateriVhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materi_vhs', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('desc');
            $table->text('type');
            $table->unsignedBigInteger('jadwal_id');
            $table->text('image');
            $table->text('file');
            $table->text('video');
            $table->softDeletes();
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
        Schema::dropIfExists('materi_vhs');
    }
}
