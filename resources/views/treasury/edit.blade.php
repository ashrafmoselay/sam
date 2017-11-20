@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>['treasury.update',$item->id],'method'=>'put']) !!}
				<div class="form-group">
					<label for="">{{ trans('app.Title') }}</label>
					<input name="title" type="text" class="form-control" required="required" value="{{$item->title}}" placeholder="{{ trans('app.Title') }}">
    				
				</div>
				<div class="form-group">
					<label for="">{{trans('app.Client Name')}}</label>
					<select id="client_id" name="client_id"  class="form-control">
					<option value="0"></option>
					@foreach(\App\Clients::get() as $clien)
						<option {{ ($clien->id==$item->client_id)?'selected=""':'' }}  value="{{$clien->id}}">{{$clien->name}}</option>
					@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="">{{ trans('app.value') }}</label>
					<input name="value" value="{{$item->value}}"  min="0" type="number" step="0.01"  class="form-control"  placeholder="{{ trans('app.value') }}">
    				
				</div>
				<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@stop()