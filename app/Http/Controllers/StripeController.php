<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

use Exception;

class StripeController extends Controller
{
   
    public function processpayment(Request $request)
{
    Stripe::setApiKey('sk_test_51KtYRUD3HS4vNAwa7ANL32HQqRTywhV3JHWIp3BxAIHv04bWoz22aKlRs9Q1L6znSX2i5fu5i3Xkl9i2Goz7jAkC00LL0T3lTL');
  
    try {
        $charge = Charge::create([
            'amount' => $request->amount,
            'currency' => 'usd',
            'description' => 'Paiement via Stripe', // Description facultative
            'source' => $request->token, 
        ]);

        // Le paiement a réussi
        return response()->json(['message'=>'Paid Successfully'], 200);
        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}

    
}
