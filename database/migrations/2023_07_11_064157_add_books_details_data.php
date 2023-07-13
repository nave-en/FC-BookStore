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
        if (!Schema::hasTable("book_details")) {
            Log::info("Books table not exist in the database");
        }

        $date = date('Y-m-d h:i:s');
        try {
            DB::table("book_details")->insert([
               ["name" => "ABC", "description" => "ABC Description", "isbn_code" => "A1B1C1", 
               "rating" => 3.8, "no_of_pages" => 122, "created_at" => $date, "updated_at" => $date],
               ["name" => "DEF", "description" => "DEF Description", "isbn_code" => "A1B1C2",
               "rating" => 4.5, "no_of_pages" => 200, "created_at" => $date, "updated_at" => $date],
               ["name" => "GHI", "description" => "GHI Description", "isbn_code" => "A1B1C3",
               "rating" => 4.8, "no_of_pages" => 400, "created_at" => $date, "updated_at" => $date],
               ["name" => "JKL", "description" => "JKL Description", "isbn_code" => "A1B1C4",
               "rating" => 3.2, "no_of_pages" => 320, "created_at" => $date, "updated_at" => $date],
               ["name" => "MNO", "description" => "MNO Description", "isbn_code" => "A1B1C5",
               "rating" => 4.1, "no_of_pages" => 80, "created_at" => $date, "updated_at" => $date]
            ]);
        } catch(\Error $err) {
            Log::info("Failed to insert the data in the book_details table . Error : " . $err->getMessage());

            return;
        }
        Log::info("Data is successfully inserted to the book_details table");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable("book_details")) {
            try {
                DB::table("book_details")->delete();
            } catch(\Error $err) {
                Log::info("Failed to delete the data in the book_details table. Error : " . $err->getMessage());
                return;
            }

            Log::info("Rows are deleted from the book_details table");
        }
    }
};
