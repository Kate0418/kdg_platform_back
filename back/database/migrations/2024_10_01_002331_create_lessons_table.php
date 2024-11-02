<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("lessons", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("subject_id");
            $table->unsignedBigInteger("course_id");
            $table->time("start_time");
            $table->integer("day_of_week")->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("lessons");
    }
};
