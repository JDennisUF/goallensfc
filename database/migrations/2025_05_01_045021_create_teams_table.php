<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id(); // Internal Laravel ID
            $table->unsignedBigInteger('api_id')->unique(); // ID from the API
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('country')->nullable();
            $table->string('logo_url')->nullable();
            $table->boolean('national')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
}

