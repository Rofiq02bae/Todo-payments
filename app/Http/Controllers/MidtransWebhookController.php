<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MidtransWebhookService;

class MidtransWebhookController extends Controller
{
    //
    public function __construct(MidtransWebhookService $service)
    {
    }

    public function handle(Request $request)
    {
        $payment = $this->service->handle($request->all());

        return response()->json([
        'success' => true,
        'payment' => $payment
    ]);
 }
}

