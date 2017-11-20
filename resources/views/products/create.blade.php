@extends('layouts.app')
@section('content')
<div class="container">
@include('products.form',['formAttr'=>['route'=>'products.store','method'=>'post']])
</div>

@include('products.unit',['allunit' => \App\Unit::get()])
@stop()