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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('password');
            $table->string('api_username')->nullable();
            $table->string('api_password')->nullable();
            $table->string('api_ads_sender')->nullable();
            $table->string('api_notify_sender')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('paid')->default(false);
            $table->string('paid_amount')->nullable();
            $table->string('paid_ref')->nullable();
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
