@extends('layouts.app')
@section('content')
@include('orders.style')
<div class="container">
    <div class="row"> 	 
		<div class="col-md-12 orderContainer">
			@include('orders.form',['formAttr'=>['route'=>['orders.update',$item->id],'method'=>'put','class'=>'orderForm',"role"=>"form", "data-toggle"=>"validator"]])
		</div>
	</div>
</div>
@stop()
@include('orders.js')
@include('orders.clone')

