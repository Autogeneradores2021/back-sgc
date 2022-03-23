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
            $table->string('upgrade_plan_type_code', 50);
            $table->foreign('upgrade_plan_type_code')->on('upgrade_plan_types')->references('code');
            $table->foreignId('request_id')->constrained('requests');
            $table->foreignId('person_assigned_id')->constrained('users');;
            $table->datetime('init_date');
            $table->datetime('end_date');
            $table->string('unit_measurement')->nullable();
            $table->string('goal_description')->nullable();
            $table->longText('follow_process_description');
            $table->datetime('finish_date')->nullable();
            $table->longText('evidence_file')->nullable();
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
