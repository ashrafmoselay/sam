@extends('layouts.app')
@section('content')
<style type="text/css">
	.qtystyle{
	    position: absolute;
	    font-size: 12px;
	    display: none;
	    min-width: 24px;
	    max-width: 25px;
	}
	span.afqty {
	    margin-top: -35px;
	    margin-right: 70px;
	    padding: 4px;
	}
	span.tqty {
	    margin-right: -70px;
	    margin-top: 35px;
	    padding: 4px;
	}
	input.qty{
		text-align: center;
	}
</style> 
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>['returns..update',$item->id],'method'=>'put']) !!}
				<div class="form-group col-md-6">
					<label for="">{{ trans('app.Supplier Name') }}</label>
					<select name="supplier_id"  class="form-control" required="required">
					@foreach(\App\Suppliers::get() as $supplier)
						<?php 
							if($item->supplier_id!=$supplier->id) continue; 
						?>
						<option {{($item->supplier_id==$supplier->id)?'selected=""':''}}  value="{{$supplier->id}}">{{$supplier->name}}</option>
					@endforeach
					</select>
				</div>
				<div class="form-group col-md-6">
					<label for="">التاريخ</label>
					<input  name="created_at" value="{{$item->created_at}}"  type="text" class="form-control datepicker"  required="required" placeholder="التاريخ">
				</div>
				@foreach($item->details as $key=>$prod)
					<div class="row productList">
					  <div class="col-md-6">
					  	<div class="form-group">
							<label for="">{{ trans('app.Products') }} </label>
							<input value="{{$prod->product_id}}-{{$prod->product->title}}" name="product_id[]" class="typeahead form-control" required=""  type="text">
    						
						</div>
					  </div>
					  <div class="col-md-2">
					  	<div class="form-group">
							<label for="">{{ trans('app.Cost Price') }}</label>
							<input value="{{$prod->cost}}"  name="cost[]" class="form-control cost" required=""  min="0" type="number" step="0.01" >
    						
						</div>
					  </div>
					  <div class="col-md-1">
					  	<div class="form-group">
							<label for="">{{ trans('app.Qantity') }}</label>
							<input value="{{$prod->qty}}"  name="quantity[]" class="form-control qty" required=""  min="0" type="number" step="0.01" >
    						
						</div>
					  </div>
					  <div class="col-md-2">
					  	<div class="form-group">
							<label for="">{{ trans('app.Total') }} </label>
							<input value="{{$prod->total}}"  name="totalcost[]" class="form-control total" required="" min="0" type="number" step="0.01" >
    						
						</div>
					  </div>
					  <div style="margin-top: 32px;"  class="col-md-1">
					  		@if($key>0)
							<a rel="{{$prod->id}}" class="btn btn-danger" href="#" role="button">-</a>
							@else	
					  		<a class="btn btn-primary addnewproducts" href="#" role="button">+</a>
							@endif
					  </div>
					</div>
				@endforeach
				<div class="form-group col-md-3">
					<label for="">{{ trans('app.Total') }}</label>
					<input name="total" readonly="" value="{{$item->total}}" min="0" type="number" step="0.01"  class="form-control totalCost"  placeholder="{{ trans('app.Total') }}">
    				
				</div>
				<div class="form-group col-md-3">
					<label for="">قيمة الخصم</label>
					<input name="discount"  min="0" type="number" step="0.01" value="{{$item->discount}}"  class="form-control discount" required="required"  placeholder="قيمة الخصم">
				</div>
				<div class="form-group col-md-3">
					<label for="">{{trans('app.Paid')}}</label>
					<input name="paid"  min="0" type="number" step="0.01"  class="form-control paid" required="required" value="{{$item->paid}}"  placeholder="{{trans('app.Paid')}}">
    				
				</div>
				<div class="form-group col-md-3">
					<label for="">{{trans('app.Due')}}</label>
					<input name="due" readonly="" type="number" step="0.01"  class="form-control due" value="{{$item->due}}"  placeholder="{{trans('app.Due')}}">
    				
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
    initTypeahead();
	$(document).on("click",".btn-danger",function(e){
		e.preventDefault();
		$(this).closest('div').parent('div').remove();
		calculateCost();
	});
	$(".addnewproducts").click(function(e){
		e.preventDefault();
		var clone = $('.cloneDiv').html();
		$(".productList:last:visible").after(clone);
		initTypeahead();
	});
	$(document).on("input",".cost,.qty",function(e){
		e.preventDefault();
		var parent = $(this).closest('div').parents('.productList');
		var qty = parseInt(parent.find(".qty").val());
		var cost = parseFloat(parent.find(".cost").val());
		var total = qty * cost; 
		if(isNaN(total))total=0;
		parent.find(".total").val(total);
		calculateCost();
	});
	$(document).on("input",".paid,.totalCost,.discount",function(e){
		e.preventDefault();
		var totalCost = parseFloat($(".totalCost").val());
		var discount = parseFloat($(".discount").val());
		var requirdValue = totalCost - discount;
		var paid = parseFloat($(".paid").val());
		var due = requirdValue - paid;
		if(isNaN(due))due=requirdValue;
		$(".due").val(due);
	});
});
function initTypeahead(){
	var path = "{{ route('autocomplete') }}";
	$('input.typeahead').typeahead({
        source:  function (query, process) {
        return $.get(path, { query: query }, function (data) {
                return process(data);
            });
        },
	    updater:function (item) {
	        return item;
	    },

        afterSelect: function (item) {
	        var code = item.name;
	        $("input.typeahead:visible").each(function(){
			 	var val = $(this).val();
			 	var elm = val.indexOf(code);

			 	if(elm != -1){
            		$(this).parents('div.productList').find('input.originalprice').val(item.cost);
            		var avilable = item.quantity-item.sale_count;
            		$(this).parents('div.productList').find('span.tqty').text(avilable);
            		$(this).parents('div.productList').find('span.afqty').text(avilable+1);
            		$(this).parents('div.productList').find(".qtystyle").slideDown(); 
            		$(".cost").trigger('input');
            		return false;
			 	}
			});
        },
    });	
}
function calculateCost(){
	var totalCost =0;
	$(".total:visible").each(function() {
		if (!isNaN($(this).val())) {
			totalCost += parseFloat($(this).val());
		}
	});
	$(".totalCost").val(totalCost);
	$(".paid").trigger('input');
}
</script>
@stop()

<div style="display: none;" class="row cloneDiv">
	<div class="row productList">
	  <div class="col-md-6">
	  	<div class="form-group">
			<label for="">{{ trans('app.Products') }} </label>
			<input name="product_id[]" autocomplete="off" class="typeahead form-control" required=""  type="text">
			
		</div>
	  </div>
	  <div class="col-md-2">
	  	<div class="form-group">
			<label for="">{{ trans('app.Cost Price') }}</label>
			<input name="cost[]" autocomplete="off" class="form-control cost originalprice" required=""  type="number" step="0.01" min="0">
			
		</div>
	  </div>
	  <div class="col-md-1">
	  	<div class="form-group">
			<label for="">{{ trans('app.Qantity') }}</label>
			<span class="tqty qtystyle btn btn-success"></span> 
			<input value="1" name="quantity[]" autocomplete="off" class="form-control qty" required="" min="0"  type="number" step="0.01">
			<span class="afqty qtystyle btn btn-warning"></span>
		</div>
	  </div>
	  <div class="col-md-2">
	  	<div class="form-group">
			<label for="">{{ trans('app.Total') }} </label>
			<input name="totalcost[]" class="form-control total" required="" min="0"  type="number" step="0.01">
			
		</div>
	  </div>
	  <div style="margin-top: 32px;" class="col-md-1">
			<a class="btn btn-danger" href="#" role="button">-</a>
	  </div>
	</div>
</div>