<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('book_publications')) {
            Schema::create('book_publications', function (Blueprint $table) {
                $table->id();
                $table->integer('book_id');
                $table->integer('publication_id');
                $table->timestamps();
            });

            Log::info('book_publications table is successfully added to the database');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_publications');
        Log::info('book_publications table was successfully dropped from the database');
    }
};
