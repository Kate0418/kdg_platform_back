<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendsTable extends Migration
{
    public function up(): void
    {
        Schema::create("attends", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->unsignedBigInteger("student_id");
            $table->unsignedBigInteger("lesson_id");
            $table->date("date");
            $table->enum("status", ["present", "late", "absent"]);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("attends");
    }
}
