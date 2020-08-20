<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequirementCertificateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requirement_certificate_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requirement_certificate_id');
            $table->string('name');
            $table->boolean('required')->default(true);
            $table->unsignedInteger('order')->default(1);
            $table->string('type')->default('text');
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
        Schema::dropIfExists('requirement_certificate_items');
    }
}
