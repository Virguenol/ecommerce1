<?php

namespace App\Http\Controllers;

use Cart;
use App\Models\Product;
use Illuminate\Http\Request;
//use Illuminate\Routing\Controller;

class CartController extends Controller
{
    public function add(Product $product)
    {
        // add the product to cart
        \Cart::session(auth()->id())->add(array(
            'id' => $product->id,
            'name' => $product->name,
            'description'=> $product->description,
            'price' => $product->price,
            'quantity' => 1,
            'attributes' => array(),
            'associatedModel' => $product
        ));



        return redirect()->route('cart.index');

    }

    public function index()
    {

        $cartItems = \Cart::session(auth()->id())->getContent();


        return view('cart.index', compact('cartItems'));
    }

    public function destroy($itemId)
    {

       \Cart::session(auth()->id())->remove($itemId);

        return back();
    }

    public function update($itemId)
    {

        \Cart::session(auth()->id())->update($itemId, [
            'quantity' => [
            'relative' => false,
            'value' => request('quantity')
            ]
        ]);

        return back();
    }

    public function checkout()
    {
        return view('cart.checkout');
    }

   /* public function applyCoupon()
    {
        $couponCode = request('coupon_code');

        $couponData = Coupon::where('code', $couponCode)->first();

        if(!$couponData) {
            return back()->withMessage('Sorry! Coupon does not exist');
        }


        //coupon logic
        $condition = new \Darryldecode\Cart\CartCondition(array(
            'name' => $couponData->name,
            'type' => $couponData->type,
            'target' => 'total',
            'value' => $couponData->value,
        ));

        Cart::session(auth()->id())->condition($condition); // for a speicifc user's cart


        return back()->withMessage('coupon applied');

    }*/
}
