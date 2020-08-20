<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantRegistrationRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_registration_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_requirement_id');
            $table->unsignedBigInteger('registration_participant_id');
            $table->string('value');
            $table->boolean('validation');
            $table->text('note')->nullable();
            $table->dateTime('validated_at')->nullable();
            $table->unsignedBigInteger('admin_id');
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
        Schema::dropIfExists('participant_registration_requirements');
    }
}
