<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Orders extends Model
{
    use HasFactory;
    protected $fillable = ["user_id", "book_stock_id", "count", "price", "status"];

    /**
     * Method to checkout the cart
     * @param array $cartItems
     * @return bool
     */
    public function proceedToCheckout($cartItems) : bool
    {
        return DB::transaction(function () use ($cartItems) {
            $idsToDelete = [];
            try {
                foreach ($cartItems as $cartItem) {
                    self::create([
                        "user_id" => $cartItem["user_id"],
                        "book_stock_id" => $cartItem["book_stock_id"],
                        "count" => $cartItem["count"],
                        "price" => $cartItem["price"],
                        "status" => 3
                    ]);
                    array_push($idsToDelete, $cartItem["id"]);
                }
            } catch (\Error $err) {
                Log::error(
                    "Failed to add records in the orders table, record : " . json_encode($cartItems) .
                    ". Error : " . $err->getMessage()
                );

                return false;
            }

            $deleteStatus = (new Cart())->deleteRecords($idsToDelete);

            if (!$deleteStatus) {
                return false;
            }

            return true;
        });
    }
}
