@extends('layouts.app')
@section('content')
@include('orders.style') 
<div class="container">
    <div class="row">
        <div class="col-md-12 orderContainer">
        @include('purchaseInvoice.form',['formAttr'=>['route'=>'purchaseInvoice.store','method'=>'post']])
			
		</div>
	</div>
</div>
@stop()
@include('purchaseInvoice.js')
@include('purchaseInvoice.clone')