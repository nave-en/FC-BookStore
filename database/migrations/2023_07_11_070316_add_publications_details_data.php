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
        if (!Schema::hasTable("publication_details")) {
            Log::info("publication_details table not exist in the database");
        }

        $date = date('Y-m-d h:i:s');
        try {
            DB::table("publication_details")->insert([
                ["name" => "Asia", "created_at" => $date, "updated_at" => $date],
                ["name" => "Balcony", "created_at" => $date, "updated_at" => $date],
                ["name" => "Raj", "created_at" => $date, "updated_at" => $date],
                ["name" => "South India", "created_at" => $date, "updated_at" => $date],
                ["name" => "Tree", "created_at" => $date, "updated_at" => $date],
            ]);
        } catch(\Error $err) {
            Log::info("Failed to insert the data in the publication_details table . Error : " . $err->getMessage());
            return;
        }
        Log::info("Data is successfully inserted to the publication_details table");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable("publication_details")) {
            try {
                DB::table("publication_details")->delete();
            } catch(\Error $err) {
                Log::info("Failed to delete the data in the publication_details table. Error : " . $err->getMessage());
                return;
            }

            Log::info("Rows are deleted from the publication_details table");
        }
    }
};
