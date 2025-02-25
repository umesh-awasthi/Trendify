<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobalConfigurationsTable extends Migration
{
    public function up()
    {
        Schema::create('global_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('api_url');
            $table->timestamps();
            $table->string('selection')->nullable(); // New column for selection
        });
    }

    public function down()
    {
        Schema::dropIfExists('global_configurations');
    }
}
