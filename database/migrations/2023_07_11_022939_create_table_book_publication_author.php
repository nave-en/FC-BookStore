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
        if (!Schema::hasTable('book_publication_author')) {
            Schema::create('book_publication_author', function (Blueprint $table) {
                $table->id();
                $table->integer('book_author_id');
                $table->integer('book_publication_id');
                $table->timestamps();
            });

            Log::info('book_publication_author table is successfully added to the database');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_publication_author');
        
        Log::info('book_publication_author table was successfully dropped from the database');
    }
};
