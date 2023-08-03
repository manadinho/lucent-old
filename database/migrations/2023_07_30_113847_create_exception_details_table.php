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
        Schema::create('exception_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exception_id')->references('id')->on('exceptions')->onDelete('cascade');
            $table->longText('trace');
            $table->longText('code_snippet');
            $table->longText('request');
            $table->longText('user');
            $table->longText('app');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exception_details');
    }
};
