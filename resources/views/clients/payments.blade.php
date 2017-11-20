@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>'addpay','method'=>'post']) !!}
				<div class="form-group col-md-6">
					<label for="">{{ trans('app.Client Name') }}</label>
					<input disabled="" value="{{$client->name}}" type="text" class="form-control">
					<input value="{{$client->id}}" name="client_id" type="hidden" class="form-control" >
				</div>
				<div class="form-group col-md-6">
					<label for="">تاريخ الدفع</label>
					<input  name="created_at" value="{{date('Y-m-d')}}"  type="text" class="form-control datepicker"  required="required" placeholder="التاريخ">
    				
				</div>
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.Total') }}</label>
					<input readonly="" name="total" value="{{$client->due}}"  min="0" type="number" step="0.01" class="form-control total" required="required" placeholder="{{ trans('app.Total') }}">
    				
				</div>
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.Paid') }}</label>
					<input name="paid" min="0" type="number" step="0.01" class="form-control paid" required="required" placeholder="{{ trans('app.Paid') }} ">
    				
				</div>
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.Due') }}</label>
					<input name="due" readonly="" min="0" type="number" step="0.01" class="form-control due" required="required" placeholder="{{ trans('app.Due') }}">
    				
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
		$('.datepicker').datepicker({format: 'yyyy-mm-dd',rtl: true});
		$(document).on("input",".paid",function(e){
			e.preventDefault();
			var due = parseFloat($(".total").val()) - parseFloat($(".paid").val());
			$(".due").val(due);
		});
	});
</script>
@stop()