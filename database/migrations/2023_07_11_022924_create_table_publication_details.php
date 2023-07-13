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
        if (!Schema::hasTable('publication_details')) {
            Schema::create('publication_details', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });

            Log::info('publications_details table is successfully added to the database');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publication_details');
        Log::info('publications_details table is successfully dropped from the database');
    }
};
