@extends('layouts.app')
@section('content')
@include('orders.style')
<div class="container">
    <div class="row">
        <div class="col-md-12 orderContainer">
        	@include('purchaseInvoice.form',['formAttr'=>['route'=>['purchaseInvoice.update',$item->id],'method'=>'put']])
		</div>
	</div>
</div>
@stop()
@include('purchaseInvoice.js')
@include('purchaseInvoice.clone')
