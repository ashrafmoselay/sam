@extends('layouts.app')
@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-12">
			<h4>{{$item->name}}</h4>
			{!! Form::open(['route'=>'withdraw','method'=>'post']) !!}
				<input type="hidden" name="bank_id" value="{{$id}}">
				<div class="form-group col-md-4">
					<label for="">البيان</label>
					<input required="" class="form-control" value="{{$item->note}}" type="text" name="note">
				</div>
				<div class="form-group col-md-4">
					<label for="">تاريخ السحب او الإيداع</label>
					<input  name="op_date" value="{{date('Y-m-d')}}"  type="text" class="form-control datepicker"  required="required" placeholder="التاريخ">
    				
				</div>
				<div class="form-group col-md-4">
					<label for="">{{trans('app.Opertion Type')}}</label>
					<select id="type" name="type"  class="form-control">
						<option value="1">{{trans('app.withdraw')}}</option>
						<option value="2">{{trans('app.deposite')}}</option>
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="">رصيد الحساب</label>
					<input readonly="" name="total" value="{{$item->balance}}"  min="0" type="number" step="0.01" class="form-control total" required="required">
    				
				</div>
				<div class="form-group col-md-4">
					<label for="">المبلغ</label>
					<input required="" name="value" min="0" type="number" step="0.01" class="form-control paid" required="required" placeholder="{{ trans('app.Paid') }} ">
    				
				</div>
				<div class="form-group col-md-4">
					<label for="">رصيد بعد العملية</label>
					<input name="due" readonly="" min="0" type="number" step="0.01" class="form-control due" required="required" placeholder="{{ trans('app.Due') }}">
    				
				</div>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
				</div>
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
            var due = parseFloat($(".total").val());
			if($("#type").val()==1) {
                due -= parseFloat($(".paid").val());
            }else{
                due += parseFloat($(".paid").val());
			}
			$(".due").val(due);
		});
        $(document).on("change","#type",function(e){
            $(".paid").trigger('input')
		});
	});
</script>
@stop()