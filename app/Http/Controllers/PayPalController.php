<?php

namespace App\Http\Controllers;

use App\Mail\OrderPaid;
use App\Models\Order;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PayPalController extends Controller
{
    public function getExpressCheckout($orderId)
    {
        $checkoutData = $this->checkoutData($orderId);
        $provider = new ExpressCheckout;
        $response = $provider->setExpressCheckout($checkoutData);

        return redirect($response['paypal_link']);
    }

    private function checkoutData($orderId)
    {
        $cart = \Cart::session(auth()->id());

    $cartItems = array_map( function($item){
        return[
            'name'=>$item['name'],
            'price'=>$item['price'],
            'qty'=>$item['quantity']
        ];
    }, $cart->getContent()->toarray());

        $checkoutData = [
        'items'      => $cartItems,
        'return_url' =>route('paypal.success', $orderId),
        'cancel_url' =>route('paypal.cancel'),
        'invoice_id' =>uniqid(),
        'invoice_description'=> "order description",
        'total'=> $cart->getTotal()
    ];

     return $checkoutData;

    }

    public function cancelPage()
    {
        dd('payment failed');
    }

    public function getExpressCheckoutSuccess(Request $request, $orderId)
    {
        $token = $request('token');
        $payerId = $request->get('PayerID');
        $provider = new ExpressCheckout;
        $checkoutData = $this->checkoutDate($orderId);

        $response = $provider->getExpressCheckoutDetails($token);

        if(in_array(strtoupper($response['ACK']), ['Success', 'SUCCESSWITHWARNING'])) {

            //Perform transaction on Paypal
            $payment_status = $provider->doExpressCheckoutPayment($checkoutData, $token, $payerId);
            $status = $payment_status['PAYEMENT_0_PAYMENSTATUS'];
           if(in_array($status, ['Completed', 'Processed'])) {
               $order = Order::find($orderId);
               $order->is_paid = 1;
               $order->save();

               //envoi du mail
               Mail::to($order->user->email)->send(new OrderPaid($order));


               return redirect()->route('home')->withMessage('Payment successful!');
           }

        }

        return redirect()->route('home')->withError('Payment UnSuccessful! Something went wrong!');

    }


}
