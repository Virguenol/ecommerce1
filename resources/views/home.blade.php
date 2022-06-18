@extends('layouts.app')

@section('content')
   <div class="container text-center">
       <h2>Products</h2>
       <div class="row">

           @foreach ($products as $product)
           <div class="col-4">
             <div class="card">
                 <img  src="product.jpg" alt="{{ $product->name }}">
                 <div>
                     <h4 class="cart-title">{{ $product->name }}</h4>
                     <p class="cart-title">{{ $product->description }}</p>
                     <h3>{{ $product->price }} Fcfa</h3>
                 </div>
                 <div class="card-body">
                     <a href="{{ route('cart.add', $product->id) }}" class="card-link"> Ajouter au panier</a>
                 </div>
             </div>
            </div>
            @endforeach
          </div>
   </div>
@endsection
