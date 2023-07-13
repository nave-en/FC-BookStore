<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Orders;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Method to add new records to the cart
     * @param Request $request
     * @return string
     */
    public function add(Request $request) : string
    {
        $requestData = $request->all();
        if (empty($requestData)) {
            return json_encode([
                "status" => false,
                "message" => "Empty data provided."
            ]);
        }

        $cartModel = new Cart();
        $validationStatus = $cartModel->validateRequestData($requestData);

        if ($validationStatus['status'] == false) {
            return json_encode($validationStatus);
        }

        $insertStatus = $cartModel->addRecords($requestData);

        if (!$insertStatus) {
            return json_encode(["message" => "Failed to add the record in the cart."]);
        }

        return json_encode(["message" => "Record was successfully add to the cart."]);
    }

    /**
     * Method to view the items in the cart
     * @param int $userId
     * @return string
     */
    public function viewCart($userId) : string
    {
        return json_encode(
            (new Cart())->getUserCartDetails($userId)
        );
    }

    /**
     * Method to checkout the cart
     * @param int $userId
     * @return string
     */
    public function checkout($userId) : string
    {
        $cartItems = (new Cart())->getUserCartDetails($userId);
        if (empty($cartItems)) {
            return json_encode([
                "status" => false,
                "message" => "Cart is empty."
            ]);
        }

        $status = (new Orders())->proceedToCheckout($cartItems);
        if (!$status) {
            return json_encode(
                [
                    "status" => false,
                    "message" => "Failed to place the order."
                ]
            );
        }

        return json_encode(
            [
                "status" => true,
                "message" => "Order was successfully placed."
            ]
        );
    }

    /**
     * Method to update the record in the cart
     * @param Request $request
     * @return string
     */
    public function updateCount(Request $request) : string
    {
        $requestData = $request->all();
        if (empty($requestData)) {
            return json_encode(
                [
                    "status" => false,
                    "message" => "Input data cannot be empty"
                ]
            );
        }
        $cartModel = new Cart();
        $validationStatus = $cartModel->validateUpdateRequest($requestData);
        if (!$validationStatus["status"]) {
            return json_encode($validationStatus);
        }

        $modifyStatus = $cartModel->modifyCartDetails($requestData);
        if (!$modifyStatus) {
            return json_encode([
                "status" => false,
                "message" => "Failed to update the cart details"
            ]);
        }

        return json_encode([
            "status" => true,
            "message" => "Successfully update the cart details"
        ]);
    }

    /**
     * Method to delete the record in the cart
     * @param int $cartId
     * @return string
     */
    public function delete($cartId) : string
    {
        $deleteStatus = (new Cart())->deleteRecordInCart([$cartId]);
        if (!$deleteStatus) {
            return json_encode([
                "status" => false,
                "message" => "Failed to delete the record."
            ]);
        }

        return json_encode([
            "status" => true,
            "message" => "Record was successfully deleted."
        ]);
    }

    /**
     * Method to delete all items from the cart
     * @param int $stringId
     * @return string
     */
    public function deleteAll($userId) : string
    {
        $deleteStatus = (new Cart())->deleteRecordsByUserId($userId);
        if (!$deleteStatus) {
            return json_encode([
                "status" => false,
                "message" => "Failed to delete the records in the cart."
            ]);
        }

        return json_encode([
            "status" => true,
            "message" => "Records in the cart successfully deleted."
        ]);
    }
}
