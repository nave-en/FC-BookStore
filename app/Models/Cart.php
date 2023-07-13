<?php

namespace App\Models;

use App\Models\BookPublicationAuthor;
use App\Models\Stocks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Cart extends Model
{
    use HasFactory;
    protected $table = "cart";
    protected $fillable = ["book_stock_id", "user_id", "price", "count"];
    protected $stockDetails;
    protected $cartDetails;

    /**
     * Method to validate the create request data
     * @param array $requestData
     * @return array
     */
    public function validateRequestData($requestData) : array
    {
        if (!isset($requestData["book_stock_id"])
            || !isset($requestData["count"])
            || !isset($requestData["user_id"])
        ) {
            return [
                "status" => false,
                "message" => "Keys not found."
            ];
        }

        $count = $requestData["count"];
        $bookStockId = $requestData["book_stock_id"];
        $this->stockDetails = (new Stocks())->getStockDetails($bookStockId);
        
        if (empty($this->stockDetails)) {
            return [
                "status" => false,
                "message" => "Stock record not found."
            ];
        }

        if ($count <= 0) {
            return [
                "status" => false,
                "message" => "Ordered count cannot be less than or equal to zero."
            ];
        } elseif ($count > $this->stockDetails["available_count"]) {
            return [
                "status" => false,
                "message" => "Ordered count cannot be greater than available count."
            ];
        }

        return ["status" => true];
    }

    /**
     * Method to validate the update request data
     * @param array $requestData
     * @return array
     */
    public function validateUpdateRequest($requestData) : array
    {
        if (!isset($requestData["id"])
            || !isset($requestData["book_stock_id"])
            || !isset($requestData["count"])
            || !isset($requestData["user_id"])
        ) {
            return [
                "status" => false,
                "message" => "Keys not found"
            ];
        }

        $this->stockDetails= (new Stocks())->getStockDetails($requestData["book_stock_id"]);
        $this->cartDetails = $this->getCartDetailsById($requestData["id"]);

        if (empty($this->stockDetails)) {
            return [
                "status" => false,
                "message" => "Stock record not found."
            ];
        }

        if (empty($this->cartDetails)) {
            return [
                "status" => false,
                "message" => "Cart record not found."
            ];
        }

        $count = $requestData["count"];
        $cartCount = $this->cartDetails["count"];

        if ($count == 0) {
            return [
                "status" => false,
                "message" => "Count cannot be zero"
            ];
        } elseif ($count < 0) {
            if ((-1 * $count) > $cartCount) {
                // records removed from the existing cart
                return [
                    "status" => false,
                    "message" => "Removal item is greater than the item in the cart."
                ];
            }
        } else {
            // when count is increased to the existing cart item
            if ($count + $cartCount > $this->stockDetails["available_count"]) {
                return [
                    "status" => false,
                    "message" => "Ordered count cannot be greater than available count."
                ];
            }
        } 

        return ["status" => true];
    }

    /**
     * Method to add records in the table
     * @param array $insertData
     * @return bool
     */
    public function addRecords($insertData) : bool
    {
        $count = $insertData["count"];
        $userId = $insertData["user_id"];
        $bookStockId = $insertData["book_stock_id"];
        $totalPrice = $this->stockDetails["price"] * $count;
        return DB::transaction(function () use (
            $count,
            $bookStockId,
            $userId,
            $totalPrice
        ) {
            $updateStatus = (new Stocks())->updateRecords($bookStockId, $this->stockDetails["available_count"] - $count);
            if (!$updateStatus) {
                return false;
            }

            $this->cartDetails = $this->getCartDetailsByBookStockId($bookStockId, $userId);
            if (empty($this->cartDetails)) {
                try {
                    self::create([
                        "book_stock_id" => $bookStockId,
                        "user_id" => $userId,
                        "price" => $totalPrice,
                        "count" => $count
                    ]);
                } catch (\Error $err) {
                    Log::error(
                        "Failed to add data in the cart table, book stock id : " . $bookStockId . " user_id : "
                        . $userId . " , price : " . $totalPrice . " and count : " . $count .
                        ". Error : " . $err->getMessage()
                    );

                    return false;
                }
            } else {
                if ($count + $this->cartDetails["count"] > $this->stockDetails["available_count"]) {
                    Log::error(
                        "Ordered count cannot be greater than available count. Insert data : " . json_encode($insertData)
                    );

                    return false;
                }
                $updateStatus = $this->updateCartDetails($this->stockDetails["price"], $count);

                if (!$updateStatus) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Function to modify existing cart items
     * @param array $requestData
     * @return bool
     */
    public function modifyCartDetails($requestData) : bool
    {
        return DB::transaction(function () use ($requestData) {
            $bookStockId = $requestData["book_stock_id"];
            $count = $requestData["count"];
            $updateStatus = (new Stocks())->updateRecords($bookStockId, $this->stockDetails["available_count"] - $count);
            if (!$updateStatus) {
                return false;
            }
            $updateStatus = $this->updateCartDetails($this->stockDetails["price"], $count);
            if (!$updateStatus) {
                return false;
            }

            return true;
        });
    }

    /**
     * Method to update the cart details
     * @param array $cartDetails
     * @param int $price
     * @param int $count
     * @return bool
     */
    public function updateCartDetails($price, $count) : bool
    {
        $totalPrice = ($price * $count) + $this->cartDetails["price"];
        $totalCount = $count + $this->cartDetails["count"];
        if ($totalCount == 0) {
            return $this->deleteRecords([$this->cartDetails["id"]]);
        }
        
        return $this->updateRecords($this->cartDetails["id"], $totalCount, $totalPrice);
    }

    /**
     * Method to get the cart details by using book stock id
     * @param int $bookStockId
     * @param int $userId
     * @return string
     */
    public function getCartDetailsByBookStockId($bookStockId, $userId)
    {
        $cartDetails = self::where("book_stock_id", $bookStockId)
            ->where("user_id", $userId)
            ->first();
        
        if (empty($cartDetails)) {
            return [];
        }

        return $cartDetails->toArray();
    }

    /**
     * Method to update the records in the cart table
     * @param int $cartId
     * @param int $count
     * @param int $price
     * @return bool
     */
    public function updateRecords($cartId, $count, $price) : bool
    {
        try {
            self::where("id", $cartId)
                ->update([
                    "count" => $count,
                    "price" => $price
                ]);
        } catch (\Error $err) {
            Log::error(
                "Failed to update the records for book_stock_id : " . $bookStockId . ", count : " . $count .
                ", price : " . $price . ". Error : " . $err->getMessage()
            );
            
            return false;
        }

        return true;
    }

    /**
     * Method to get the cart details by id
     * @param int $id
     * @return string
     */
    public function getCartDetailsById($id) : array
    {
        $cartDetails =  self::where("id", $id)
            ->first();
        
        if (empty($cartDetails)) {
            return [];
        }

        return $cartDetails->toArray();
    }

    /**
     * Method to get the user cart details
     * @param int $userId
     * @return array
     */
    public function getUserCartDetails($userId) : array
    {
        return self::where("user_id", $userId)
            ->get()
            ->toArray();
    }

    /**
     * Method to delete the records
     * @param int $idsToDelete
     * @return bool
     */
    public function deleteRecords($idsToDelete) : bool
    {
        try {
            self::WhereIn("id", $idsToDelete)
                ->delete();
        } catch (\Error $err) {
            Log::error("Failed to delete ids : " . json_encode($idsToDelete) . " from the table stock. Error : " . $err->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Method to get the expired records
     * @return array
     */
    public function getExpiredRecords() : array
    {
        $yesterday = date('Y-m-d h:i:s', strtotime("-1 days"));
        return self::whereDate("updated_at", '<', $yesterday)
            ->get()
            ->toArray();
    }

    /**
     * Method to delete records using user id
     * @param int $userId
     * @return bool
     */
    public function deleteRecordsByUserId($userId) : bool
    {
        try {
            self::where("user_id", $userId)
                ->delete();
        } catch (\Error $err) {
            Log::error("Failed to delete the cart records for the user id : " . $userId);

            return false;
        }

        return true;
    }

    /**
     * Method to delete the record in the cart
     * @param int $cartId
     * @return bool
     */
    public function deleteRecordInCart($cartId) : bool
    {
        $cartDetails = $this->getCartDetailsById($cartId);
        if (empty($cartDetails)) {
            return true;
        }

        $stockModel = new Stocks();
        $stockDetails = $stockModel->getStockDetails($cartDetails["book_stock_id"]);
        $transactionStatus = DB::transaction (function () use($stockModel, $cartDetails, $stockDetails, $cartId) {
            $updateStatus = $stockModel->updateRecords($stockDetails["id"], $stockDetails["available_count"] + $cartDetails["count"]);
            if (!$updateStatus) {
                return false;
            }
            
            return $this->deleteRecords([$cartId]);
        });

        return $transactionStatus;
    }

    /**
     * Method to delete all the records in the user cart
     * @param integer $userId
     * @return bool
     */
    public function deleteAllRecordsInCart($userId) : bool
    {
        $cartDetails = $this->getUserCartDetails($userId);
        $stockModel = new Stocks();
        if (empty($cartDetails)) {
            return true;
        }
        
        return DB::transaction (function () use($stockModel, $cartDetails, $userId) {
            foreach ($cartDetails as $cartDetail) {
                $stockDetail = $stockModel->getStockDetails($cartDetail["book_stock_id"]);
                $updateStatus = $stockModel->updateRecords($stockDetail["id"], $stockDetail["available_count"] + $cartDetail["count"]);
                if (!$updateStatus) {
                    return false;
                }
            }

            return $this->deleteRecordsByUserId($userId);
        });
    }
}
