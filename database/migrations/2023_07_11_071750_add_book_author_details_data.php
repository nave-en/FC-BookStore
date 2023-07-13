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
        if(!Schema::hasTable("book_author_details")) {
            Log::info("book_author_details table not exist in the database");
            return;
        }

        $date = date('Y-m-d h:i:s');
        try {
            DB::table("book_author_details")->insert([
                ["book_id" => 1, "author_id" => 1, "created_at" => $date, "updated_at" => $date],
                ["book_id" => 2, "auhtor_id" => 6, "created_at" => $date, "updated_at" => $date],
                ["book_id" => 3, "author_id" => 4, "created_at" => $date, "updated_at" => $date],
                ["book_id" => 4,  "author_id" => 2, "created_at" => $date, "updated_at" => $date],
                ["book_id" => 4, "author_id" => 1, "created_at" => $date, "updated_at" => $date],
                ["book_id" => 5, "author_id" => 5, "created_at" => $date, "updated_at" => $date]
            ]);
        } catch(\Error $err) {
            Log::info("Failed to insert the data in the book_author_details. Error : " . $err->getMessage());
            return;
        }
        Log::info("Data are successfully inserted into the book_author_details.");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable("book_author_details")) {
            try {
                DB::table("book_author_details")->delete();
            } catch(\Error $err) {
                Log::info("Failed to delete records from the table book_author_details. Error : " . $err->getMessage());
                return;
            }

            Log::info("Records are successfully deleted from the table book_author_details.");
        }
    }
};
