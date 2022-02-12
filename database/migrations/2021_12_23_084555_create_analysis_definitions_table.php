<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalysisDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracking_id')->constrained('trackings')->cascadeOnDelete();
            $table->string('problem_understand', 3);
            $table->string('local_revision', 3);
            $table->string('data_review', 3);
            $table->string('viability_test', 3);
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
        Schema::dropIfExists('analysis_definitions');
    }
}
