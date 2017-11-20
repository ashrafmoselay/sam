@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>['store.update',$item->id],'method'=>'put']) !!}
				@include('store.form')
			{!! Form::close() !!}
		</div>
	</div>
</div>
@stop()