<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable("author_details")) {
            Log::info("author_details table not exist in the database");
            return;
        }

        $date = date('Y-m-d h:i:s');
        try {
            DB::table("author_details")->insert([
                ["name" => "Albert", "created_at" => $date, "updated_at" => $date],
                ["name" => "Charles", "created_at" => $date, "updated_at" => $date],
                ["name" => "George", "created_at" => $date, "updated_at" => $date],
                ["name" => "Kalki", "created_at" => $date, "updated_at" => $date],
                ["name" => "Krista", "created_at" => $date, "updated_at" => $date],
                ["name" => "Sophie", "created_at" => $date, "updated_at" => $date]
            ]);
        } catch(\Error $err) {
            Log::info("Failed to insert the data in the author_details table . Error : " . $err->getMessage());
            return;
        }
        Log::info("Data is successfully inserted to the author_details table");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable("author_details")) {
            try {
                DB::table("author_details")->delete();
            } catch(\Error $err) {
                Log::info("Failed to delete the data in the author_details table. Error : " . $err->getMessage());
                return;
            }

            Log::info("Rows are deleted from the author_details table");
        }
    }
};
