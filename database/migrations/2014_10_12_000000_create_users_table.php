<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('');
            $table->string('username')->default('');
            $table->string('avatar')->default('');
            $table->string('spotify_token')->default('');
            $table->string('spotify_refresh_token')->default('');
            $table->datetime('spotify_token_expires')->nullable();
            $table->string('slack_token')->nullable();
            $table->string('slack_user_id')->nullable();
            $table->string('slack_webhook_url')->nullable();
            $table->timestamps();
        });
    }
}
