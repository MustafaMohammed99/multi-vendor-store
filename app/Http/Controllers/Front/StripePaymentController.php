<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\SetupIntent;
use Stripe\Stripe;

class StripePaymentController extends Controller

{


    public function create(Order $order)
    {
        return view('front.payment', [
            'order' => $order,
        ]);
    }

    public function createPaymentIntent(Order $order)
    {
        // $s=  Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        // dd(config('services.stripe.secret_key'));
        $amount = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $amount = round($amount);

        $stripe = App::make('stripe.client');

        // return [
        //     'amount' => $amount
        // ];
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $amount,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);


        Payment::create([
            'order_id' => $order->id,
            'amount' => $paymentIntent->amount,
            'currency' => $paymentIntent->currency,
            'method' => 'stripe',
            'status' => 'pending',
            'transaction_id' => $paymentIntent->id,
            'transaction_data' => json_encode($paymentIntent),
        ]);


        return [
            'clientSecret' => $paymentIntent->client_secret,
            'amount' => $amount
        ];
    }

    public function confirm(Request $request, Order $order)
    {

        /**
         * @var \Stripe\StripeClient
         */
        $stripe = App::make('stripe.client');
        $paymentIntent = $stripe->paymentIntents->retrieve(
            $request->query('payment_intent'),
            []
        );


        if ($paymentIntent->status == 'succeeded') {
            try {
                // Update payment
                $payment = Payment::where('order_id', $order->id)->first();



                $payment->forceFill([
                    'status' => 'completed',
                    'transaction_data' => json_encode($paymentIntent),
                ])->save();
            } catch (QueryException $e) {
                echo $e->getMessage();
                return;
            }

            // event('payment.created', $payment->id);

            return redirect()->route('home', [
                'status' => 'payement-succeeded'
            ]);
        }

        return redirect()->route('orders.payments.create', [
            'order' => $order->id,
            'status' => $paymentIntent->status,
        ]);
    }




    /*
    public function createPaymentIntent()
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));

        $order = Order::find(104);
        $amount = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
        ]);

        return response()->json(['client_secret' => $paymentIntent->client_secret]);
    }

    public function confirmPayment(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));

        $paymentIntent = PaymentIntent::retrieve($request->payment_intent);

        $paymentIntent->confirm([
            'payment_method' => $request->payment_method,
        ]);

        return response()->json(['status' => $paymentIntent->status]);
    }

    */
}
