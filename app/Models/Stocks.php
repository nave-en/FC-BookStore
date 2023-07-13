<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Stocks extends Model
{
    use HasFactory;

    /**
     * Method to get the stock details
     * @param int $id
     * @return array
     */
    public function getStockDetails($id) : array
    {
        $stockDetails =  self::where("id", $id)
            ->first();

        if ($stockDetails == null) {
            return [];
        }

        return $stockDetails->toArray();
    }

    /**
     * Method to update the records
     * @param int $bookStockId
     * @param int $count
     * @return bool
     */
    public function updateRecords($bookStockId, $count) : bool
    {
        try {
            self::where("id", $bookStockId)
                ->update(["available_count" => $count]);
        } catch (\Error $err) {
            Log::error(
                "Failed to update the count for the bpa id : " . $bookStockId .
                " and the count :" . $count . " and Error : " . $err->getMessage()
            );

            return false;
        }

        return true;
    }

    /**
     * Method to get the stock details for multipleIds
     * @param array $ids
     * @return array
     */
    public function getStockDetailsforMultipleIds($ids) : array
    {
        return self::whereIn("id", $ids)
            ->get()
            ->keyBy("id")
            ->toArray();
    }
}
