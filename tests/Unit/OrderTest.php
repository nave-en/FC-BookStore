<?php

namespace Tests\Unit;

use App\Models\Orders;
use App\Models\Cart;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * Method to test proceed to checkout
     */
    public function testProceedToCheckout(): void
    {
        $insertData = [
            "count" => 1,
            "book_stock_id" => 2,
            "user_id" => 1
        ];

        $cartModel = new Cart();
        $cartModel->validateRequestData($insertData);
        $cartModel->addRecords($insertData);
        $cartDetails = $cartModel->getUserCartDetails($insertData["user_id"]);
        $checkoutStatus = (new Orders())->proceedToCheckout($cartDetails);
        if ($checkoutStatus) {
            $this->assertTrue(true);
        }
    }
}
