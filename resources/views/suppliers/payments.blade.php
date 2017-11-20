@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>'supplierpay','method'=>'post']) !!}
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.Supplier Name') }}</label>
					<input disabled="" value="{{$supplier->name}}" type="text" class="form-control">
					<input value="{{$supplier->id}}" name="supplier_id" type="hidden" class="form-control" >
				</div>
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.esal_num') }}</label>
					<input name="esal_num" value="" type="text" class="form-control">
				</div>
				<div class="form-group col-md-4">
					<label for="">تاريخ الدفع</label>
					<input  name="created_at" value="{{date('Y-m-d')}}"  type="text" class="form-control datepicker"  required="required" placeholder="التاريخ">
    				
				</div>
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.Total') }}</label>
					<input readonly="" name="total" value="{{$supplier->due}}"  type="text" class="form-control total" required="required" placeholder="{{ trans('app.Total') }}">
				</div>
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.Paid') }}</label>
					<input name="paid" type="text" class="form-control paid" required="required" placeholder="{{ trans('app.Paid') }} ">
				</div>
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.Due') }}</label>
					<input name="due" readonly="" type="text" class="form-control due" required="required" placeholder="{{ trans('app.Due') }}">
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
		$(document).on("input",".paid",function(e){
			e.preventDefault();
			var due = parseFloat($(".total").val()) - parseFloat($(".paid").val());
			$(".due").val(due);
		});
	});
</script>
@stop()