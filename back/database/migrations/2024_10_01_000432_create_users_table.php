<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->tinyInteger("type");
            $table->unsignedBigInteger("company_id");
            $table->string("first_password");
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn(["type", "company_id", "first_password"]);
            $table->dropSoftDeletes();
        });
    }
};
