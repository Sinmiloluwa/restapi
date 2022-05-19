@extends('layouts.app')

@section('content')
<div class="container mt-3">
  <h2>Products</h2>            
  <table class="table table-dark">
    <thead>
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $product)
      <tr>
        <td>{{$product->name}}</td>
        <td>{{$product->description}}</td>
        <td>{{$product->price}}</td>
        <form action="{{route('pay')}}" method="post">
          @csrf
          <input type="hidden" value="{{$product->name}}" name="name"/>
          <input type="hidden" value="{{$product->description}}" name="description"/>
          <input type="hidden" value="{{$product->price}}" name="price"/>
          <input type="hidden" value="simons@gmail.com" name="email"/>
          <input type="hidden" value="08123456786" name="phone"/>
        <td><button class="btn btn-success" type="submit">Pay</button></td>
        </form>
      </tr>
     @endforeach
    </tbody>
  </table>
</div>
@endsection