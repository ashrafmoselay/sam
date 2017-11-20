@extends('layouts.app')
@section('content')
<style type="text/css">
	.productList{    
		border-top: 2px solid #d0d0d0;
    	margin-bottom: 15px;
	    background-color: #f7f7f7;
	    position: relative;
	    padding: 10px;
	}
	.btnx{
	    position: absolute;
	    top: 0;
	    left: 0;
	}
</style> 
@php 
$units = \App\Unit::get();
$stores = \App\Store::get(); 
@endphp	
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>'returns.store','method'=>'post']) !!}
				<div class="form-group col-md-3">
					<label for="">{{ trans('app.Supplier Name') }}</label>
					<select data-show-subtext="true" data-live-search="true" name="supplier_id"  class="form-control selectpicker" required="required">
					<option value="">{{ trans('app.--- Select Supplier ---') }}</option>
					@foreach(\App\Suppliers::get() as $supplier)
						<option value="{{$supplier->id}}">{{$supplier->name}} {{($supplier->due>0)?"( هذا المورد له $supplier->due ج.م )":''}}</option>
					@endforeach
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="">إختر المخزن</label>

					<select name="store_id" id="store_id" class="form-control" required="required">
						
						@foreach($stores as $s)
							<option value="{{$s->id}}">{{$s->address}}</option>
						@endforeach
					</select>
					
				</div>  
				<div class="form-group col-md-3">
					<label for="">التاريخ</label>
					<input  name="created_at" value="{{date('Y-m-d')}}"  type="text" class="form-control datepicker"  required="required" placeholder="التاريخ">
    				
				</div>
				<div class="form-group col-md-3">
					<label for="">خصم من حساب المورد ( أجل )</label>
					<input class="form-control" type="checkbox" name="is_subtract">    				
				</div>
				<div class="col-md-12 productList">
				  <div class="col-md-3">
				  	<div class="form-group">
						<label for="">{{ trans('app.Products') }} </label>
						<input name="product_id[]" autocomplete="off" class="typeahead form-control" required=""  type="text">
    					
					</div>
				  </div>
				  <div class="col-md-3">
				  	<div class="form-group">
						<label for="">{{ trans('app.Cost Price') }}</label>
						<input name="cost[]" autocomplete="off" step="0.01" class="form-control cost originalprice" required=""  min="0" type="number" >
    					
					</div>
				  </div>
				  <div class="col-md-3">
				  	<div class="form-group">
						<label for="">{{ trans('app.Qantity') }} </label>
						<div class="input-group">
						<input value="1" min="0" name="quantity[]" autocomplete="off" class="form-control qty" required=""  type="number" step="0.01">
						
						  <span class="input-group-addon">
						  	@include('unit.dropdown',['selId'=>'','allunit'=>$units])
						  </span> 
						 </div>
					</div>
				  </div>
				  <div class="col-md-3">
				  	<div class="form-group">
						<label for="">{{ trans('app.Total') }} </label>
						<input name="totalcost[]" class="form-control total" step="0.01" required="" min="0" type="number" step="0.01" >
    					
					</div>
				  </div>
				  <div class="btnx">
				  		<a class="btn btn-primary addnewproducts" href="#" role="button">+</a>
				  </div>
				</div>
				<div class="form-group col-md-12"> 
					<label for="">{{ trans('app.Total') }}</label>
					<input name="total" readonly="" min="0" type="number" step="0.01" step="0.01" class="form-control totalCost"  placeholder="{{ trans('app.Total') }}">
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

	$(document).on("input",".qty",function(e){
		e.preventDefault();
		var parent = $(this).closest('div.form-group');
		var total = parent.find('.tqty').text();
		var reqqty = $(this).val();
		var afqty = parseInt(total) - parseInt(reqqty);
		parent.find('.afqty').text(afqty);
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
  $(document).on("change",".unitclass",function(e){
    e.preventDefault();
    var cost = $(this).find("option:selected").attr("rel");
    $(this).closest('div.productList').find('.originalprice').val(cost);
    $(this).closest('div.productList').find('.qty').trigger('input');
  });
});
function initTypeahead(){
	var path = "{{ route('autocomplete') }}";
	$('input.typeahead').typeahead({
        source:  function (query, process) {
          var unit = $(this.$element).closest('div.productList').find('.unit').val();
        return $.get(path, { query: query,store_id: $('#store_id').val(),unit:unit }, function (data) {
        		
                return process(data);
            });
        },
	    updater:function (item) {
	        return item;
	    },
        afterSelect: function (item) {

          var elm = $(this.$element);

          elm.parents('div.productList').find('.unitclass').attr('cost',item.cost_price);
          elm.parents('div.productList').find('.unitclass').attr('unitid',item.unitid);
          elm.parents('div.productList').find('.unitclass').attr('title',item.title);
          //elm.parents('div.productList').find('.unitclass').attr('price',item.price);
          //elm.parents('div.productList').find('.unitclass').attr('price2',item.price2);
          //elm.parents('div.productList').find('.unitclass').attr('price3',item.price3);
          var unitid = item.unitid.split(",");
          var title = item.title.split(",");
          var cost = item.cost_price.split(",");
          //var price = item.price.split(",");
          //var price2 = item.price2.split(",");
          //var price3 = item.price3.split(",");
          elm.parents('div.productList').find('.unitclass').html("");
          option = "";
          storUnitName = "";
          for(i=0;i<unitid.length;i++){
            if(item.storeID==unitid[i]){
              storUnitName = title[i];
            }
            option += "<option rel='"+cost[i]+"' value = '"+unitid[i]+"'>"+title[i]+"</option>"
          }
          elm.parents('div.productList').find('.unitclass').html(option);
          $(".unitclass").trigger("change");
          var qty = parseFloat(item.quantity).toFixed(2);
          if(isInt(item.quantity)){
            qty = item.quantity;
          }
          elm.closest('div.productList').find('.avilableQty').text("متاح:"+qty+" "+storUnitName);
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
}
</script>
@stop()


<div style="display: none;" class="row cloneDiv">
	<div class="col-md- productList">
	  <div class="col-md-3">
	  	<div class="form-group">
			<label for="">{{ trans('app.Products') }} </label>
			<input name="product_id[]" autocomplete="off" class="typeahead form-control" required=""  type="text">
			
		</div>
	  </div>
	  <div class="col-md-3">
	  	<div class="form-group">
			<label for="">{{ trans('app.Cost Price') }}</label>
			<input name="cost[]" autocomplete="off" class="form-control cost originalprice" required=""  type="number" step="0.01" min="0">
			
		</div>
	  </div>
	  <div class="col-md-3">
	  	<div class="form-group">
			<label for="">{{ trans('app.Qantity') }} </label>
			<div class="input-group"> 
			<input value="1" min="0" name="quantity[]" autocomplete="off" class="form-control qty" required=""  type="number" step="0.01">
			
			  <span class="input-group-addon">
			  	@include('unit.dropdown',['selId'=>'','allunit'=>$units])
			  </span> 
			 </div>
		</div>
	  </div>
	  <div class="col-md-3">
	  	<div class="form-group">
			<label for="">{{ trans('app.Total') }} </label>
			<input name="totalcost[]" class="form-control total" required="" min="0"  type="number" step="0.01">
			
		</div>
	  </div>
	  <div class="btnx">
			<a class="btn btn-danger" href="#" role="button">-</a>
	  </div>
	</div>
</div>