<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleFormActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_form_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_form_id')->constrained('role_forms')->cascadeOnDelete();
            $table->string('action_code', 50)->nullable();
            $table->foreign('action_code')->on('actions')->references('code')->cascadeOnDelete();
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
        Schema::dropIfExists('role_form_actions');
    }
}
