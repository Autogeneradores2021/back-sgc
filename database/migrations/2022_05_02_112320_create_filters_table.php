<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_types', function (Blueprint $table) {
            $table->string('code', 50)->primary();
            $table->string('name', 80);
            $table->string('description', 80);
            $table->timestamps();
        });

        Schema::create('filter_values', function (Blueprint $table) {
            $table->string('code', 50)->primary();
            $table->string('name', 80);
            $table->string('description', 80);
            $table->longText('query', 80);
            $table->timestamps();
        });

        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->string('value', 50);
            $table->foreign('value')->on('filter_values')->references('code');
            $table->string('type', 50);
            $table->foreign('type')->on('filter_types')->references('code');
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
        Schema::dropIfExists('filters');
    }
}
