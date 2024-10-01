<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('password');
            $table->tinyInteger('type');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->string('email')->unique();
            $table->string('first_password');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
