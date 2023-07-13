<?php

namespace App\Console\Commands;

use App\Models\Stocks;
use App\Models\Cart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteExpiredCartItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-expired-cart-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the records in the cart table which is added or update one day from the current day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recordsToDelete = (new Cart())->getExpiredRecords();
        $stockModel = new Stocks();
        $idsToDelete = [];
        $recordsToUpdate = [];
        foreach ($recordsToDelete as $record) {
            $bookStockId = $record["book_stock_id"];
            if (!array_key_exists($bookStockId, $recordsToUpdate)) {
                $recordsToUpdate[$bookStockId] = $record["count"];
            } else {
                $recordsToUpdate[$bookStockId] += $record["count"];
            }
            array_push($idsToDelete, $record["id"]); 
        }

        $transactionStatus = DB::transaction(function() use ($recordsToUpdate, $stockModel, $idsToDelete) {
            $bookStockIds = array_keys($recordsToUpdate);
            $bookStockDetails = $stockModel->getStockDetailsforMultipleIds($bookStockIds);
            try {
                foreach ($recordsToUpdate as $id => $count) {
                    $count += $bookStockDetails[$id]["count"];
                    $updateStatus = $stockModel->updateRecords($id, $count);
                    if (!$updateStatus) {
                        return false;
                    }
                }

                (new Cart())->whereIn("id", $idsToDelete)
                    ->delete();
            } catch(\Error $err) {
                Log::error("Error Occured while cron job. Error : " . $err->getMessage());

                return false;
            }

            return true;
        });

        if ($transactionStatus) {
            Log::info("Records which are successfully deleted are : " . json_encode($recordsToDelete));
        }
    }
}
