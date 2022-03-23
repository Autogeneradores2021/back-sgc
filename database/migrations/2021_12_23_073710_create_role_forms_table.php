<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_forms', function (Blueprint $table) {
            $table->id();
            $table->string('role_code', 50)->nullable();
            $table->foreign('role_code')->on('roles')->references('code');
            $table->string('form_code', 50)->nullable();
            $table->foreign('form_code')->on('forms')->references('code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_forms');
    }
}
