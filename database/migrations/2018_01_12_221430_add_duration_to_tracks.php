<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDurationToTracks extends Migration
{
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->integer('duration_ms')->nullable();
        });
    }
}
