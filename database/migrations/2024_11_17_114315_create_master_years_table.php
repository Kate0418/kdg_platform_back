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
        Schema::create("master_years", function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->tinyInteger('status')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table("master_years")->insert([
            ["name" => "1年制", "status" => 1],
            ["name" => "2年制", "status" => 2],
            ["name" => "3年制", "status" => 3],
            ["name" => "4年制", "status" => 4],
            ["name" => "5年制", "status" => 5],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("master_years");
    }
};
