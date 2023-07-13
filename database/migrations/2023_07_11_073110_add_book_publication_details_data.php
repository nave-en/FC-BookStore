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
        if (!Schema::hasTable("book_publications")) {
            Log::info("book_publications table not exist in the database");
            return;
        }

        $date = date('Y-m-d h:i:s');
        try {
            DB::Table("book_publications")->insert([
                ["book_id" => 1, "publication_id" => 5, "created_at" => $date, "updated_at" => $date],
                ["book_id" => 1, "publication_id" => 3, "created_at" => $date, "updated_at" => $date],
                ["book_id" => 2, "publication_id" => 4, "created_at" => $date, "updated_at" => $date],
                ["book_id" => 3, "publication_id" => 1, "created_at" => $date, "updated_at" => $date],
                ["book_id" => 4, "publication_id" => 2, "created_at" => $date, "updated_at" => $date],
                ["book_id" => 5, "publication_id" => 1, "created_at" => $date, "updated_at" => $date]
            ]);
        } catch(\Error $err) {
            Log::info("Failed to add records in the book_publications table. Error : " . $err->getMessage());
        }

        Log::info("Records are successfully inserted to the table books_publications");   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable("book_publications")) {
            try {
                DB::Table("book_publications")->delete();
            } catch (\Error $err) {
                Log::info("Failed to delete records from the table book_publications. Error : " . $err->getMessage());
                return;
            }
            Log::info("Records are successfully deleted from the table books_publications");
        }
    }
};
