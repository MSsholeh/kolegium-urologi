<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantExamRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_exam_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_requirement_id');
            $table->unsignedBigInteger('exam_participant_id');
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
        Schema::dropIfExists('participant_exam_requirements');
    }
}
