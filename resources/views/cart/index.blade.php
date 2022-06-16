@extends('layouts.app')

@section('content')

    <h2>mon panier</h2>


        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
        @foreach ($cartItems as $item )

                <tr>
                    <td scope="row">{{ $item->name }}</td>
                    <td>
                        {{\Cart::session(auth()->id())->get($item->id)->getPriceSum() }}
                    </td>
                    <td>
                        <from action="{{ route('cart.update', $item->id) }}">
                            <input name="quantity" type="number" value="{{ $item->quantity }}">

                            <input type="submit" value="save">
                        </from>
                    </td>
                    <td>
                        <a href="{{ route('cart.destroy', $item->id) }}">Supprimer</a>
                    </td>
                </tr>
              @endforeach
            </tbody>
        </table>
        <h3>
            Prix Total : {{ Cart::session(auth()->id())->getTotal() }}
        </h3>

<a class="btn btn-primary" href="{{ route('cart.checkout') }}" role="button">Proceed to checkout</a>
@endsection
