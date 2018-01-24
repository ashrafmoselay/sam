@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>'bank.store','method'=>'post']) !!}
				@include('bank.form')
			{!! Form::close() !!}
		</div>
	</div>
</div>
@stop()
