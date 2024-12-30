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
        Schema::create("times", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("course_id");
            $table->integer("period")->unsigned();
            $table->time("start_time");
            $table->time("end_time");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("times");
    }
};
