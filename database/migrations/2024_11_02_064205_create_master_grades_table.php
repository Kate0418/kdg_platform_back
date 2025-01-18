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
        Schema::create("master_grades", function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->tinyinteger("status")->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table("master_grades")->insert([
            ["name" => "卒業後", "status" => 99],
            ["name" => "入学前", "status" => 0],
            ["name" => "1年次", "status" => 1],
            ["name" => "2年次", "status" => 2],
            ["name" => "3年次", "status" => 3],
            ["name" => "4年次", "status" => 4],
            ["name" => "5年次", "status" => 5],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("master_grades");
    }
};
