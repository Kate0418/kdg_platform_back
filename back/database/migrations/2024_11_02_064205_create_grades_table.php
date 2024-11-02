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
        Schema::create("grades", function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table("grades")->insert([
            ["name" => "1年次"],
            ["name" => "2年次"],
            ["name" => "3年次"],
            ["name" => "4年次"],
            ["name" => "5年次"],
            ["name" => "卒業後"],
            ["name" => "入学前"],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("grades");
    }
};
