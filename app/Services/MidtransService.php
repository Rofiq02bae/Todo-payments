<?php
namespace App\Services;

use App\Models\Payment;
use App\Models\Todo;
use Midtrans\Snap;
use Midtrans\Config;

class MidtransService
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createPayment(Todo $todo) : Payment
    {
        $existingPayment = Payment::where('todo_id', $todo->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPayment) {
            return $existingPayment;
        }

        $order_id = 'TODO-'.now()->format('YmdHis').'-'.$todo->id;
        $item_price = 100; // Set the price according to your needs
        $todo_title = 'Export PDF Todo'; // Set the item name according to your needs
        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $item_price, // Set the amount according to your needs
            ],
            'item_details' => [
                [
                    'id' => $todo->id,
                    'price' => $item_price, // Set the price according to your needs
                    'quantity' => 1,
                    'name' => $todo_title,
                ],
            ],
            
                'customer_details' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'user@example.com',
            ],
            ];

        $snapToken = Snap::getSnapToken($params);

        return Payment::create([
            'todo_id' => $todo->id,
            'order_id' => $order_id,
            'amount' => $item_price, // Set the amount according to your needs
            'status' => 'pending',
            'snap_token' => $snapToken,
        ]);
    }
}