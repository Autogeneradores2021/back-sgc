<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_type_code', 10);
            $table->dateTime('init_date');
            $table->dateTime('detected_date');
            $table->string('detected_in_code', 10);
            $table->unsignedBigInteger('detected_for_id');
            $table->string('unfulfilled_requirement_code', 10);
            $table->unsignedBigInteger('process_lead_id');
            $table->string('affected_process_code', 10);
            $table->string('how_detected_code', 10);
            $table->string('action_type_code', 10);
            $table->string('request_code', 30)->nullable();
            $table->longText('evidence_description');
            $table->longText('request_description');
            $table->longText('evidence_file')->nullable();
            $table->string('status_code', 10);
            $table->timestamps();

            $table->foreign('request_type_code')->cascadeOnDelete()->references('code')->on('request_types');
            $table->foreign('action_type_code')->cascadeOnDelete()->references('code')->on('action_types');
            $table->foreign('how_detected_code')->cascadeOnDelete()->references('code')->on('detection_types');
            $table->foreign('status_code')->cascadeOnDelete()->references('code')->on('status');
            $table->foreign('process_lead_id')->cascadeOnDelete()->references('id')->on('users');
            $table->foreign('detected_for_id')->cascadeOnDelete()->references('id')->on('users');
            $table->foreign('detected_in_code')->cascadeOnDelete()->references('code')->on('detected_places');
            $table->foreign('unfulfilled_requirement_code')->cascadeOnDelete()->references('code')->on('unfulfilled_requirements');
            $table->foreign('affected_process_code')->cascadeOnDelete()->references('code')->on('affected_processes');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
