<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantCourseRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_course_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_requirement_id');
            $table->unsignedBigInteger('course_participant_id');
            $table->string('value');
            $table->boolean('validation');
            $table->text('note');
            $table->dateTime('validated_at')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
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
        Schema::dropIfExists('participant_course_requirements');
    }
}
