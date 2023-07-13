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
        if (!Schema::hasTable('cart')) {
            Schema::create('cart', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('book_stock_id');
                $table->integer('count');
                $table->integer('price');
                $table->timestamps();
            });

            Log::info('cart table is successfully added to the database.');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
        Log::info('cart table is successfully dropped from the database.');
    }
};
