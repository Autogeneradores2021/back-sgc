<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnSystemToSelectables extends Migration
{

    public $toCreate = [
        # Request selectables
        'status',
        'request_types',
        'detected_places',
        'unfulfilled_requirements',
        'affected_processes',
        'detection_types',
        'action_types',
        # Upgrade plans
        'upgrade_plan_types',
        # Tracking Isuss
        'icons',
        # Finish request
        'result_types',
        # users
        'areas',
        'positions',
        'identification_types'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->toCreate as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->boolean('own_system')->default(true);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->toCreate as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->boolean('own_system')->default(true);
            });
        }
    }
}
