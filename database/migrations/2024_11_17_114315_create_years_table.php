<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("years", function (Blueprint $table) {
            $table->id();

            $table->string("name")->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table("years")->insert([
            ["name" => "1年制"],
            ["name" => "2年制"],
            ["name" => "3年制"],
            ["name" => "4年制"],
            ["name" => "5年制"],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("years");
    }
};
