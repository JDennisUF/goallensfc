<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cached_leagues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('league_id')->unique();
            $table->string('name');
            $table->string('type');
            $table->string('logo_url')->nullable();
            $table->string('country');
            $table->string('country_code')->nullable();
            $table->integer('season')->nullable();
            $table->boolean('current')->default(false);
            $table->json('seasons')->nullable();
            $table->timestamp('cached_at');
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index(['league_id', 'country']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cached_leagues');
    }
};
