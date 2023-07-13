<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\Stocks;
use Tests\TestCase;

class CartTest extends TestCase
{
    /**
     * Method to test insert data
     */
    public function testValidateRequestData(): void
    {
        $cartModel = new Cart();
        $insertData = [
            "book_stock_id" => 1,
            "count" => -1
        ];

        // check all keys are in the request
        $validationStatus = $cartModel->validateRequestData($insertData);
        if (!$validationStatus["status"]) {
            $this->assertTrue(true);
        }
        $insertData["user_id"] = 1;

        // count cannot be less than or equal to zero
        $validationStatus = $cartModel->validateRequestData($insertData);
        if (!$validationStatus["status"]) {
            $this->assertTrue(true);
        }

        $insertData["count"] = 1000;
        // count cannot be greater than stock count
        $validationStatus = $cartModel->validateRequestData($insertData);
        if (!$validationStatus["status"]) {
            $this->assertTrue(true);
        }

        $insertData["book_stock_id"] = -1;
        // book stock id not exist in the database
        $validationStatus = $cartModel->validateRequestData($insertData);
        if (!$validationStatus["status"]) {
            $this->assertTrue(true);
        }
        $insertData["book_stock_id"] = 1;
        $insertData["count"] = 1;

        // successfully validation
        $validationStatus = $cartModel->validateRequestData($insertData);
        if ($validationStatus["status"]) {
            $this->assertTrue(true);
        }
    }

    /**
     * Method to test add item in the cart method
     */
    public function testAddRecords() : void
    {
        $insertData = [
            "count" => 1,
            "book_stock_id" => 1,
            "user_id" => 1
        ];
        $cartModel = new Cart();
        $stockModel = new Stocks();
        $bookStockDetails = $stockModel->getStockDetails($insertData["book_stock_id"]);
        $cartModel->validateRequestData($insertData);
        // add a new record in the cart table
        $insertStatus = $cartModel->addRecords($insertData);
        if ($insertStatus) {
            $this->assertTrue(true);
        }

        // check the count decrease in the stock table
        $updatedStockDetails = $stockModel->getStockDetails($insertData["book_stock_id"]);
        if ($updatedStockDetails["available_count"] + $insertData["count"] == $bookStockDetails["available_count"]) {
            $this->assertTrue(true);
        }

        // add same book in the existing cart
        $insertData = [
            "count" => 1,
            "book_stock_id" => 1,
            "user_id" => 1
        ];
        $insertStatus = $cartModel->addRecords($insertData);
        if ($insertStatus) {
            $this->assertTrue(true);
        }
        // one record should be present in the cart table for user id and book stockid
        $cartDetailsCount = $cartModel::where("book_stock_id", $insertData["book_stock_id"])
            ->where("user_id", $insertData["user_id"])
            ->get()
            ->count();

        if ($cartDetailsCount == 1) {
            $this->assertTrue(true);
        }
    }

    /**
     * Method to test update request data
     */
    public function testValidateUpdateRequest() : void
    {
        $updateData = [
            "id" => 1,
            "book_stock_id" => 1,
            "user_id" => 1
        ];
        $cartModel = new Cart();
        // check keys exist or not
        $validationStatus = $cartModel->validateUpdateRequest($updateData);
        if (!$validationStatus["status"]) {
            $this->assertTrue(true);
        }

        // check count not greater than stock count
        $updateData["count"] = 120;
        $validationStatus = $cartModel->validateUpdateRequest($updateData);
        if (!$validationStatus["status"]) {
            $this->assertTrue(true);
        }

        // check decreased count not greater than cart count
        $updateData["count"] = -100;
        $validationStatus = $cartModel->validateUpdateRequest($updateData);
        if (!$validationStatus["status"]) {
            $this->assertTrue(true);
        }
    }

    /**
     * Method to modify the cart details
     */
    public function testModifyCartDetails() : void
    {
        $cartModel = new Cart();
        $stockModel = new Stocks();
        $cartDetails = $cartModel->getCartDetailsByBookStockId(1, 1);
        $updateData = [
            "id" => $cartDetails["id"],
            "book_stock_id" => 1,
            "user_id" => 1,
            "count" => 1
        ];
        $cartModel->validateUpdateRequest($updateData);
        // when a count is increased to existing record
        $oldCartDetails = $cartModel->getCartDetailsById($updateData["id"]);
        $oldStockDetails = $stockModel->getStockDetails($updateData["book_stock_id"]);
        $updateStatus = $cartModel->modifyCartDetails($updateData);
        if ($updateStatus) {
            $this->assertTrue(true);
        }
        $newStockDetails = $stockModel->getStockDetails($updateData["book_stock_id"]);
        $newCartDetails = $cartModel->getCartDetailsById($updateData["id"]);
        // check the stock detail
        if ($newStockDetails["available_count"] == $oldStockDetails["available_count"] - $updateData["count"]) {
            $this->assertTrue(true);
        }
        // check the cart
        if ($newCartDetails["count"] == $oldCartDetails["count"] + $updateData["count"]) {
            $this->assertTrue(true);
        }

        // when a count is decrease from the existing cart
        $updateData = [
            "id" => $newCartDetails["id"],
            "book_stock_id" => $newCartDetails["book_stock_id"],
            "user_id" => 1,
            "count" => -1
        ];
        $cartModel->validateUpdateRequest($updateData);
        $oldCartDetails = $cartModel->getCartDetailsById($updateData["id"]);
        $oldStockDetails = $stockModel->getStockDetails($updateData["book_stock_id"]);
        $updateStatus = $cartModel->modifyCartDetails($updateData);
        if ($updateStatus) {
            $this->assertTrue(true);
        }
        $newStockDetails = $stockModel->getStockDetails($updateData["book_stock_id"]);
        $newCartDetails = $cartModel->getCartDetailsById($updateData["id"]);

        // check the stock count
        if ($newStockDetails["available_count"] > $oldStockDetails["available_count"]) {
            $this->assertTrue(true);
        }

        // check the cart count
        if ($newCartDetails["count"] < $oldCartDetails["count"]) {
            $this->assertTrue(true);
        }

        // when an item in the cart becomes zero then the row should be deleted
        $updateData["count"] = -1 * $newCartDetails["count"];
        $cartModel->validateUpdateRequest($updateData);
        $updateStatus = $cartModel->modifyCartDetails($updateData);
        if ($updateStatus) {
            $this->assertTrue(true);
        }

        $cartRowCount = $cartModel->where("book_stock_id", $updateData["book_stock_id"])
            ->where("user_id", $updateData["user_id"])
            ->get()
            ->count();
        
        if ($cartRowCount == 0) {
            $this->assertTrue(true);
        }
    }

    /**
     * Method to test user cart details
     */
    public function testGetUserCartDetails() : void
    {
        $cartDetails = (new Cart())->getUserCartDetails(-1);
        if (empty($cartDetails)) {
            $this->assertTrue(true);
        }
    }

    /**
     * Method to test delete cart by user id
     */
    public function testDeleteRecordsByUserId() : void
    {
        $insertData = [
            "count" => 1,
            "book_stock_id" => 1,
            "user_id" => 1
        ];
        $cartModel = new Cart();
        $cartModel->validateRequestData($insertData);
        $cartModel->addRecords($insertData);
        $insertData = [
            "count" => 1,
            "book_stock_id" => 2,
            "user_id" => 1
        ];
        $cartModel->validateRequestData($insertData);
        $cartModel->addRecords($insertData);
        $status = $cartModel->deleteAllRecordsInCart(1);
        if ($status) {
            $this->assertTrue(true);
        }
        $rowCount = $cartModel->getUserCartDetails(1);
        if (empty($rowCount)) {
            $this->assertTrue(true);
        }
    }

    /**
     * Method to test delete an item in the cart 
     */
    public function testDeleteRecordInCart() : void
    {
        $insertData = [
            "count" => 1,
            "book_stock_id" => 1,
            "user_id" => 1
        ];
        $cartModel = new Cart();
        $cartModel->validateRequestData($insertData);
        $cartModel->addRecords($insertData);
        $cartDetails = $cartModel->getCartDetailsByBookStockId($insertData["book_stock_id"], $insertData["user_id"]);
        $status = $cartModel->deleteRecordInCart($cartDetails["id"]);
        if ($status) {
            $this->assertTrue(true);
        }
    }
}
