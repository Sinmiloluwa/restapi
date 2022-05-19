<h3>Pay {{$product->price}}</h3>
<form method="POST" action="{{ route('pay') }}" id="paymentForm">
    {{ csrf_field() }}

    Product Name: {{$product->name}}
    <input type="hidden" value="emmasimons141@gmail.com" name="email">
    <input type="hidden" value="08097654532" name="phone">
    <input type="hidden" value="{{$product->price}}" name="price">
    <input type="hidden" value="{{$product->name}}" name="name">
    <input type="hidden" value="{{$product->description}}" name="description">

    <input type="submit" value="Pay" />
</form>