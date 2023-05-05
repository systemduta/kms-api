<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasKpisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pas_kpis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('3p_id');
            $table->unsignedBigInteger('dimensi_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->string('name');
            $table->integer('max_nilai');            
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrent();
            
            $table->foreign('3p_id')->references('id')->on('pas_3p');
            $table->foreign('dimensi_id')->references('id')->on('pas_dimensis');
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
        Schema::dropIfExists('pas_kpis');
    }
}
