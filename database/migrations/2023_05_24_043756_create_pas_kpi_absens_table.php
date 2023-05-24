<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasKpiAbsensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pas_kpi_absens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penilaianAbsen_id');
            $table->unsignedBigInteger('kpi_id');
            $table->integer('nilai');
            $table->timestamps();

            $table->foreign('penilaianAbsen_id')->references('id')->on('pas_penilaian_absens');
            $table->foreign('kpi_id')->references('id')->on('pas_kpis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pas_kpi_absens');
    }
}
