<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
    public function createPayment($data)
    {
        // Simulate payment processing logic
        $payment = new self();
        $payment->amount = $data['amount'];
        $payment->status = 'pending';
        $payment->save();

        // Simulate payment gateway response
        $payment->status = 'completed';
        $payment->save();

        return $payment;
    }
}
