<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpgradePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upgrade_plans', function (Blueprint $table) {
            $table->id();
            $table->string('upgrade_plan_type');
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->string('title', 255);
            $table->string('person_assigned', 100);
            $table->datetime('init_date');
            $table->datetime('end_date');
            $table->string('unit_measurement')->nullable();
            $table->longText('goal_description')->nullable();
            $table->longText('follow_process_description');
            $table->datetime('finish_date');
            $table->string('evidence_file')->nullable();
            $table->integer('percentage')->nullable();
            $table->string('status', 10);
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
        Schema::dropIfExists('upgrade_plans');
    }
}
