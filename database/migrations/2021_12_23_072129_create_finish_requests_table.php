<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinishRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finish_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->datetime('tracking_date');
            $table->datetime('tracking_date_period_init')->nullable();
            $table->datetime('tracking_date_period_end')->nullable();
            $table->string('result_code', 50);
            $table->foreign('result_code')->on('result_types')->references('code')->cascadeOnDelete()->nullable();
            $table->string('result_analysis', 150)->nullable();
            $table->foreignId('user_tracking_id')->constrained('users')->cascadeOnDelete()->nullable();
            $table->foreignId('user_granted_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('descriptions')->nullable();
            $table->string('objective')->nullable();
            $table->integer('total_review')->nullable();
            $table->integer('total_agree')->nullable();
            $table->integer('total_disagree')->nullable();
            $table->integer('total_fulfilment')->nullable();
            $table->integer('percentage')->nullable();
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
        Schema::dropIfExists('finish_requests');
    }
}
