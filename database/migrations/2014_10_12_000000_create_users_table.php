<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('nik');
            $table->unsignedBigInteger('university_id')->nullable();
            $table->string('nim')->nullable();
            $table->unsignedInteger('year');
            $table->unsignedTinyInteger('semester');
            $table->unsignedTinyInteger('competency');
            $table->string('pob');
            $table->date('dob');
            $table->text('address');
            $table->string('phone');
            $table->string('no_sertifikat')->nullable();
            $table->date('date_sertifikat')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
