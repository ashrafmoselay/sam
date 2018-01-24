@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>'treasury.store','method'=>'post']) !!}
				<div class="form-group">
					<label for="">{{ trans('app.notice') }}</label>
					<input name="title" type="text" class="form-control" required="required" placeholder="{{ trans('app.notice') }}">
    				
				</div>
				<div class="form-group">
					<label for="">{{trans('app.Opertion Type')}}</label>
					<select id="type" name="type"  class="form-control">
						<option value="1">{{trans('app.withdraw')}}</option>
						<option value="2">{{trans('app.deposite')}}</option>
					</select>
				</div>
				<div class="form-group hide">
					<label for="">{{trans('app.user_type')}}</label>
					<select id="userType" name="user_type"  class="form-control">
						<option value="0"></option>
						<option value="1">{{trans('app.Clients')}}</option>
						<option value="2">{{trans('app.Suppliers')}}</option>
						<option value="3">{{trans('app.Shoraka')}}</option>
						<option selected="" value="4">{{trans('app.general')}}</option>
					</select>
				</div>
				<div class="form-group client off">
					<label for="">{{trans('app.Client Name')}}</label>
					<select data-show-subtext="true" data-live-search="true"  id="client_id" name="client_id"  class="form-control selectpicker">
						<option value="0">{{trans('app.--- Select Client ---')}}</option>
						@foreach(\App\Clients::get() as $k=>$clien)
							<option value="{{$clien->id}}">{{$clien->name}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group supplier off">
					<label for="">{{trans('app.Spplier Name')}}</label>
					<select data-show-subtext="true" data-live-search="true"  id="supplier_id" name="supplier_id"  class="form-control selectpicker">
					
					@foreach(\App\Suppliers::get() as $k=>$sup)
						<option value="{{$sup->id}}">{{$sup->name}}</option>
					@endforeach
					</select>
				</div>
				<div class="form-group partner off">
					<label for="">{{trans('app.Partner Name')}}</label>
					<select data-show-subtext="true" data-live-search="true"  id="partner_id" name="partner_id"  class="form-control selectpicker">
		
					@foreach(\App\Shoraka::get() as $k=>$par)
						<option value="{{$par->id}}">{{$par->name}}</option>
					@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="">{{ trans('app.value') }}</label>
					<input name="value" min="0" type="number" step="0.01"  class="form-control"  placeholder="{{ trans('app.value') }}">
    				
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
	$(document).on("change","#userType",function(){
		var type = $(this).val();
		$(".off").hide();
		if(type==1){
			$(".client").fadeIn();
		}else if(type==2){
			$(".supplier").fadeIn();
		}else if(type==3){
			$(".partner").fadeIn();
		}
	});
});
</script>
@stop()
