@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>['clients.update',$item->id],'method'=>'put']) !!}
				<div class="form-group col-md-12">
					<label for="">{{ trans('app.Client Name') }}</label>
					<input name="name" type="text" value="{{$item->name}}" class="form-control" required="required" placeholder="{{ trans('app.Client Name') }}">
				</div>
				<div class="form-group col-md-4">
					<label for="">نوع العميل</label>
					<select class="form-control" name="type">
						<option {{($item->type==1)?'selected=""':''}} value="1">قطاعى</option>
						<option {{($item->type==2)?'selected=""':''}} value="2">جملة</option>
						<option {{($item->type==3)?'selected=""':''}} value="3">جملة الجملة</option>
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.Mobile') }}</label>
					<input name="mobile" type="text" value="{{$item->mobile}}" class="form-control"  placeholder="{{ trans('app.Mobile') }}">
				</div>
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.qest_value') }}</label>
					<input name="qest_value" value="{{$item->qest_value}}" type="text" class="form-control"  placeholder="{{ trans('app.qest_value') }}">
				</div>
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.Total') }}</label>
					<input name="total" min="0" type="number" step="0.01" value="{{$item->total}}" class="form-control total" required=""  placeholder="{{ trans('app.Total') }}">
				</div>
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.Paid') }}</label>
					<input name="paid" min="0" type="number" step="0.01" value="{{$item->paid}}" class="form-control paid"  placeholder="{{ trans('app.Paid') }} ">
				</div>
				<div class="form-group col-md-4">
					<label for="">{{ trans('app.Due') }}</label>
					<input name="due" readonly="" type="number" step="0.01" value="{{$item->due}}" class="form-control due"  placeholder="{{ trans('app.Due') }}">
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
		$(document).on("input",".total,.paid",function(e){
			e.preventDefault();
			var due = parseFloat($(".total").val()) - parseFloat($(".paid").val());
			$(".due").val(due);
		});
	});
</script>
@stop()