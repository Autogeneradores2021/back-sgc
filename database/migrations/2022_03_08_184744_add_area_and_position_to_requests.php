<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreaAndPositionToRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->string('position_code', 50)->nullable();
            $table->foreign('position_code')->on('positions')->references('code');
            $table->string('area_code', 50)->nullable();
            $table->foreign('area_code')->on('areas')->references('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->string('position_code', 10)->nullable();
            $table->foreign('position_code')->on('positions')->references('code');
            $table->string('area_code', 10)->nullable();
            $table->foreign('area_code')->on('areas')->references('code');
        });
    }
}
