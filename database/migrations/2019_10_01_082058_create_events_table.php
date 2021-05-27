<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('number_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->string('status')->default('pending');
            $table->integer('hour')->nullable();
            $table->integer('day')->nullable();
            $table->integer('minute')->nullable();
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            $table->string('type')->default('regular');
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
        Schema::dropIfExists('events');
    }
}
