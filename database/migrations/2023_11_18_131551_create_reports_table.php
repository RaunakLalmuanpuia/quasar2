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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('employee_file')->nullable();
            $table->string('employer_file')->nullable();
            $table->string('manager_file')->nullable();
            $table->enum('employer_status', ['accepted', 'rejected', 'pending'])->default('pending')->nullable();
            $table->enum('manager_status', ['accepted', 'rejected', 'pending'])->default('pending')->nullable();
            $table->date('movement');
            $table->string('employer_feedback')->nullable();
            $table->string('manager_feedback')->nullable();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('users');
            $table->unsignedBigInteger('employer_id')->nullable();
            $table->foreign('employer_id')->references('id')->on('users');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->foreign('manager_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
