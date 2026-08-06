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
        Schema::create('devise_tvas', function (Blueprint $table) {
            $table->id();
            $table->string('pays');
            $table->string('devise');
            $table->string('taxe');
            $table->string('description');
            $table->string('societe');
            $table->string('nom_user');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devise_tvas');
    }
};
