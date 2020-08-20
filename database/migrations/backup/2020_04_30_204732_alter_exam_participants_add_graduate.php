<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterExamParticipantsAddGraduate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_participants', function (Blueprint $table) {
            $table->boolean('graduate')->nullable();
            $table->string('certified')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_participants', function (Blueprint $table) {
            $table->dropColumn('graduate');
            $table->dropColumn('certified');
        });
    }
}
