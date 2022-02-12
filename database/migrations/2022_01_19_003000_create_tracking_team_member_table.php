<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingTeamMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracking_id')->constrained('trackings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->nullable();
            $table->string('name');
            $table->string('area');
            $table->string('position');
            $table->boolean('is_lead')->default(false);
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
        Schema::dropIfExists('tracking_team_members');
    }
}
