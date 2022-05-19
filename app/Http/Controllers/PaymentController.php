<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class PaymentController extends Controller
{
    public function initialize()
    {
        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => request()->price,
            'email' => request()->email,
            'redirect_url' => route('callback'),
            'tx_ref' => $reference,
            'currency' => "USD",
            'customer' => [
                'email' => request()->email,
                "phone_number" => request()->phone,
                "name" => request()->name
            ],

            "customizations" => [
                "name" => request()->name,
                "description" => request()->description
            ]
        ];

        $payment = Flutterwave::initializePayment($data);


        if ($payment['status'] !== 'success') {
           echo 'Payment not successful';
        }

        return redirect($payment['data']['link']);
    }

    

    public function callback()
    {
        $transactionID = Flutterwave::getTransactionIDFromCallback();
        $data = Flutterwave::verifyTransaction($transactionID);

        if(DB::table('payments')->where('trx_id', $transactionID)->exists())
        {
            echo 'Transaction already exists';
        }

        elseif($data['data']['status'] == 'successful')
        {
            echo 'Payment Successful';
        }

    }

    public function webhook(Request $request)
    {
    //This verifies the webhook is sent from Flutterwave
    // $verified = Flutterwave::verifyWebhook();
    // print_r($verified);

    // // if it is a charge event, verify and confirm it is a successful transaction
    // if ($verified && $request->event == 'charge.completed' && $request->data->status == 'successful') {
    //     $verificationData = Flutterwave::verifyPayment($request->data['id']);
    //     if ($verificationData['status'] === 'success') {
    //         DB::table('payments')->insert([
    //             'trx_id' => $request->data['id'],
    //             'email' => $request->data['customer']['email'],
    //             'amount' => $request->data['amount'],
    //             'products' => $request->data['customer']['name'],

    //         ]);
    //     }
    //     echo 'not successful';
    // }

    // // if it is a transfer event, verify and confirm it is a successful transfer
    // if ($verified && $request->event == 'transfer.completed') {

    //     $transfer = Flutterwave::transfers()->fetch($request->data['id']);

    //     if($transfer['data']['status'] === 'SUCCESSFUL') {
    //         DB::table('payments')->where('trx_id', $request->data['id'])->update([
    //             'status' => 'successful'
    //         ]);

    //     } else if ($transfer['data']['status'] === 'FAILED') {
    //         DB::table('payments')->where('trx_id', $request->data['id'])->update([
    //             'status' => 'failed'
    //         ]);
    //         // revert customer balance back
    //     } else if ($transfer['data']['status'] === 'PENDING') {
    //         DB::table('payments')->where('trx_id', $request->data['id'])->update([
    //             'status' => 'pending'
    //         ]);
    //     }

    //     }
    $secretHash = env('FLW_SECRET_HASH');
    $signature = $request->header('verif-hash');
    if (!$signature || ($signature !== $secretHash)) {
        // This request isn't from Flutterwave; discard
        abort(401);
    }
    $payload = $request->all();
    // It's a good idea to log all received events.
    Log::info($payload);
    }

}
