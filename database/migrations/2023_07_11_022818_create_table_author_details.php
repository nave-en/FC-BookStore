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
        if (!Schema::hasTable('author_details')) {
            Schema::create('author_details', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();       
            });

            Log::info('author_details is successfully added to the database.');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_details');

        Log::info('author_details is successfully dropped from the database.');
    }
};
