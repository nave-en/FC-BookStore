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
        if (!Schema::hasTable("stocks")) {
            Log::info("stocks table not existed in the database");
            
            return;
        }

        $date = date('Y-m-d h:i:s');
        try {
            DB::table('stocks')->insert([
                ["book_publication_author_id" => 1, "price" => 5, "available_count" => 100, "created_at" => $date, "updated_at" => $date],
                ["book_publication_author_id" => 2, "price" => 10, "available_count" => 120, "created_at" => $date, "updated_at" => $date],
                ["book_publication_author_id" => 3, "price" => 7, "available_count" => 90, "created_at" => $date, "updated_at" => $date],
                ["book_publication_author_id" => 4, "price" => 25, "available_count" => 50, "created_at" => $date, "updated_at" => $date],
                ["book_publication_author_id" => 5, "price" => 2, "available_count" => 200, "created_at" => $date, "updated_at" => $date],
                ["book_publication_author_id" => 6, "price" => 1, "available_count" => 400, "created_at" => $date, "updated_at" => $date],
                ["book_publication_author_id" => 7, "price" => 50, "available_count" => 20, "created_at" => $date, "updated_at" => $date]
            ]);
        } catch (\Error $err) {
            Log::info("Failed to add records in the table stocks. Error : " . $err->getMessage());
            return;
        }
        Log::info("Records are successfully added to the table stocks.");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable("stocks")) {
            try {
                DB::table('stocks')->delete();
            } catch (\Error $err) {
                Log::info("Records are failed to delete from the table stocks. Error : " . $err->getMessage());
                
                return;
            }

            Log::info("Records are successfully deleted from the table stocks.");
        }
    }
};
