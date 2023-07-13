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
        if (!Schema::hasTable('book_author_details')) {
            Schema::create('book_author_details', function (Blueprint $table) {
                $table->id();
                $table->integer('book_id');
                $table->integer('author_id');
                $table->timestamps();
            });

            Log::info('book_author_details is successfully added to the database.');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_author_details');

        Log::info('book_author_details is successfully dropped from  the database.');
    }
};
