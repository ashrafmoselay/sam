@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>['cheq.update',$item->id],'method'=>'put']) !!}
				@include('cheq.form')
			{!! Form::close() !!}
		</div>
	</div>
</div>
@stop()
