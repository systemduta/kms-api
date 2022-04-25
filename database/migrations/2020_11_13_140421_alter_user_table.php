<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('organization_id');
            $table->string('nik');
            $table->string('username');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_company_id_foreign');
            $table->dropForeign('users_organization_id_foreign');

            $table->dropColumn(['company_id','organization_id','nik','username']);
        });
    }
}
