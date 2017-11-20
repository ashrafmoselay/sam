@extends('layouts.app')
@section('content')
<div class="container">
 	@include('products.form',['formAttr'=>['route'=>['products.update',$item->id],'method'=>'put']])
</div>
@include('products.unit',['allunit' => \App\Unit::get()])
@stop()