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
        if (!Schema::hasTable('book_details')) {
            Schema::create('book_details', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('description');
                $table->integer('no_of_pages');
                $table->float('rating', 8, 2);
                $table->string('isbn_code');
                $table->timestamps();
            });

            Log::info('book_details table is successfully added to the database.');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_details');
        Log::info('book_details table is successfully dropped from the database.');
    }
};
