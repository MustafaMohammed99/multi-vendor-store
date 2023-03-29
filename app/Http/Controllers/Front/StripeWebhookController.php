<?php

namespace App\Http\Controllers\Front;

use App\Events\PaymentSuccess;
use App\Events\WebhookEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StripeWebhookController extends Controller
{

    public function handle(Request $request)
    {
        Log::debug('zzzzzzzzzzzzzzzzzzzzzzzzzzWebhook event', [$request->all()]);

        $event = \Stripe\Event::constructFrom(
            json_decode($request->getContent(), true)
        );

        // Log the event
        Log::info('Webhook received: ' . $event->id);

        // Send AJAX request to frontend
        $data = [
            'success' => true,
            'message' => 'Webhook received and processed.'
        ];

        $message = [
            'id' => Auth::id() ?? Session::getId(),
            'text' => 'Payment processed successfully'
        ];

        event(new WebhookEvent($message));

        return response()->json($data);


        /*
        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        Log::debug('zzzzzzzzzzzzzzzzzzzzzzzzzzWebhook event', [$event->type]);

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                Log::debug('zzzzzzzzzzzzzzzzzzzzzzPayment succeeded', [$paymentIntent->id]);
        }
        */
    }
}
