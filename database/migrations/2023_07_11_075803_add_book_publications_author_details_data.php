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
        if (!Schema::hasTable("book_publication_author")) {
            Log::info("book_publication_author table not existed in the database");
            return;
        }

        $date = date('Y-m-d h:i:s');
        try {
            DB::table("book_publication_author")->insert([
                ["book_publication_id" => 1, "book_author_id" => 1, "created_at" => $date, "updated_at" => $date],
                ["book_publication_id" => 2, "book_author_id" => 1, "created_at" => $date, "updated_at" => $date],
                ["book_publication_id" => 3, "book_author_id" =>2, "created_at" => $date, "updated_at" => $date],
                ["book_publication_id" => 4, "book_author_id" => 3, "created_at" => $date, "updated_at" => $date],
                ["book_publication_id" => 5, "book_author_id" => 4, "created_at" => $date, "updated_at" => $date],
                ["book_publication_id" => 5, "book_author_id" => 5, "created_at" => $date, "updated_at" => $date],
                ["book_publication_id" => 6, "book_author_id" => 6, "created_at" => $date, "updated_at" => $date]
            ]);
        } catch (\Error $err) {
            Log::info("Failed to add records in the book_publication_author table. Error : " . $err->getMessage());
            
            return;
        }

        Log::info("Records are successfully added  to the book_publication_author table.");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable("book_publication_author")) {
            try {
                DB::table("book_publication_author")->delete();
            } catch (\Error $err) {
                Log::info("Failed to delete records from the table book_publication_author. Error : " . $err->getMessage());

                return;
            }
            Log::info("Records are successfully deleted from the table book_publication_author");
        }
    }
};
