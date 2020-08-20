<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrantRequirementsCertificateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrant_requirements_certificate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registrant_certificate_id');
            $table->unsignedBigInteger('requirement_certificate_item_id');
            $table->string('value');
            $table->boolean('validation')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('registrant_requirements_certificate');
    }
}
