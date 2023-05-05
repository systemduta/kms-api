<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasIndPenilaiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pas_ind_penilaians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('3p_id');
            $table->unsignedBigInteger('kpi_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->integer('nilai');            
            $table->string('grade');
            $table->string('desc');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrent();
            
            $table->foreign('3p_id')->references('id')->on('pas_3p');
            $table->foreign('kpi_id')->references('id')->on('pas_kpis');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('division_id')->references('id')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pas_ind_penilaians');
    }
}
