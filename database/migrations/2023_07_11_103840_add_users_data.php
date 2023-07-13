<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable("users")) {
            Log::info("users table not exist in the database");
            
            return;
        }
        
        $date = date('Y-m-d h:i:s');
        try {
            DB::Table("users")->insert([
                ["name" => "Astirk", "password" => "12345678", "created_at" => $date, "updated_at" => $date],
                ["name" => "Balu", "password" => "12345678", "created_at" => $date, "updated_at" => $date],
                ["name" => "Cody", "password" => "12345678", "created_at" => $date, "updated_at" => $date],
                ["name" => "Dave", "password" => "12345678", "created_at" => $date, "updated_at" => $date],
                ["name" => "Ellse", "password" => "12345678", "created_at" => $date, "updated_at" => $date]
            ]);
        } catch (\Error $err) {
            Log::info("Failed to add records in the users table");

            return;
        }

        Log::info("Records are successfully added to the users table");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable("users")) {
            try {
                DB::table("users")->delete();
            } catch (\Error $err) {
                Log::info("Failed to delete records in the user table");

                return;
            }

            Log::info("Records are successfully deleted from the table");
        }
    }
};
