@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>'masrofat.store','method'=>'post']) !!}
				<div class="form-group">
					<label for="">{{ trans('app.masrof Name') }}</label>
					<input name="name" type="text" class="form-control" required="required" placeholder="{{ trans('app.masrof Name') }}">
				</div>
				<div class="form-group">
					<label for="">{{trans('app.MasrofType')}}</label>
					<select name="type"  class="form-control masrofType" required="required">
					<option value="1">{{trans('app.general')}}</option>
					<option value="2">{{trans('app.special')}}</option>
					
					</select>
				</div> 
				<div class="form-group off">
					<label for="">{{trans('app.sName')}}</label>
					<select id="sharek_id" name="sharek_id"  class="form-control">
					<option value="0"></option>
					@foreach(\App\Shoraka::get() as $shorka)
						<option value="{{$shorka->id}}">{{$shorka->name}}</option>
					@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="">{{ trans('app.value') }}</label>
					<input name="value" type="text" class="form-control"  placeholder="{{ trans('app.value') }}">
				</div>
				<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@stop()
@section('javascript')
<script type="text/javascript">
$(document).ready(function(){  
	$(".off").hide();
	$(document).on("change",".masrofType",function(){
		var type = $(this).val();
		$(".off").hide();
		if(type==2){
			$(".off").fadeIn();
		}
	});
});
</script>
@stop()