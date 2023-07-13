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
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('book_stock_id');
                $table->integer('count');
                $table->integer('price');
                $table->enum('status', ['cancelled', 'delievered', 'departed']);
                $table->timestamps();
            });

            Log::info('orders table is successfully added to the database');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Log::info('orders table is successfully dropped from the database');
    }
};
