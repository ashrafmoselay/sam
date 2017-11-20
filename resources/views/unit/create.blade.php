@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>'unit.store','method'=>'post']) !!}
				<div class="form-group col-md-12">
					<label for="">إسم الوحدة</label>
					<input name="title" type="text" class="form-control" required="required" >
				</div>
				<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@stop()
