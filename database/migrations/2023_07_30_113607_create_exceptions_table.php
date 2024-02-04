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
        Schema::create('exceptions', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id'); 
            $table->string('name');
            $table->text('message');
            $table->text('ai_solution')->nullable();
            $table->string('code');
            $table->string('file');
            $table->string('line');
            $table->string('severity');
            $table->integer('occurrence_count')->default(1);
            $table->text('occurrence_times');
            $table->boolean('is_snoozed')->default(false);
            $table->boolean('is_resolved')->default(false);
            $table->integer('reopen_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exceptions');
    }
};
