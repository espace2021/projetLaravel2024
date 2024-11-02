<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{

public function processpayment (Request $request)
{
    try {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $request->line_items,
            'mode' => 'payment',
            'success_url' => $request->success_url,
            'cancel_url' => $request->cancel_url,
        ]);

        return response()->json(['id' => $session->id]);
        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}   
}
