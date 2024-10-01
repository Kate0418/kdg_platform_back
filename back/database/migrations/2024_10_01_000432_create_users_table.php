<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('type');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->string('first_password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['type', 'course_id', 'company_id', 'first_password']);
        });
    }
};
