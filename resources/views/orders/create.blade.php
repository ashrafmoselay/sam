@extends('layouts.app')
@section('content')
@include('orders.style')
<div class="container">
    <div class="row"> 	 
		<div class="col-md-12 orderContainer">
        @include('orders.form',['formAttr'=>['route'=>'orders.store','method'=>'post','class'=>'orderForm']])
		</div>
	</div>
</div>
@stop()
@include('orders.js')
@include('orders.clone')
