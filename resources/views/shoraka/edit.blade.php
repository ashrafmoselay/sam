@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>['shoraka.update',$item->id],'method'=>'put']) !!}
				<div class="form-group">
					<label for="">{{ trans('app.sName') }}</label>
					<input name="name" type="text" value="{{$item->name}}" class="form-control" required="required" placeholder="{{ trans('app.sName') }}">
				</div>
				<div class="form-group">
					<label for="">{{ trans('app.TotalMoney') }}</label>
					<input required="required" name="total" type="text" value="{{$item->total}}" class="form-control"  placeholder="{{ trans('app.TotalMoney') }}">
				</div>
				<div class="form-group">
					<label for="">{{ trans('app.profit_percent') }}</label>
					<input required="required" name="profit_percent" min="0" type="number" step="0.01"  value="{{$item->profit_percent}}" class="form-control"  placeholder="{{ trans('app.TotalMoney') }}">
				</div>
				<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@stop()