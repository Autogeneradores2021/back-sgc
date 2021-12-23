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
            $table->string('request_type', 3);
            $table->dateTime('init_date');
            $table->dateTime('detected_date');
            $table->string('detected_in', 50);
            $table->foreignId('detected_for_id')->constrained('users')->cascadeOnDelete();
            $table->string('unfulfilled_requirement', 100);
            $table->foreignId('process_lead_id')->constrained('users')->cascadeOnDelete();
            $table->string('process_affected', 50);
            $table->string('how_detected', 50);
            $table->string('action_type', 10);
            $table->string('request_code', 30);
            $table->longText('evidence_description');
            $table->longText('request_description');
            $table->string('evidence_file_path')->nullable();
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
        Schema::dropIfExists('requests');
    }
}
