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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('birth_date');
            $table->date('enrollment_date');
            $table->enum('status', ['active', 'inactive', 'graduated', 'suspended']);
            $table->json('history')->nullable(); // Academic history, achievements, etc.
            $table->decimal('gpa', 3, 2)->default(0.00); // Grade Point Average
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
