<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripePaymentController extends Controller
{
    public function showForm()
    {
        return view('stripe-payment');
    }

    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $intent = PaymentIntent::create([
            'amount' => 5000, // amount in cents = $50
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);

        return response()->json(['clientSecret' => $intent->client_secret]);
    }
}
