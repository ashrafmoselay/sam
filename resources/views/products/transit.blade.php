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
	.avilableQty{
		color: red;
		font-size: 12px;
	}
</style>
	@php
	$stores = \App\Store::get();
	$units = \App\Unit::get();
	@endphp
<div class="container">
	<div class="row">
	    <div class="col-xs-12">
			@if(count($errors) >0)
			<ul class="alert alert-danger">
			@foreach($errors->all() as $err)
			<li>{{$err}}</li>
			@endforeach
			</ul>
			@endif
		    <div class="flash-message">
		    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
		      @if(Session::has('alert-' . $msg))

		      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		      @endif
		    @endforeach
		  </div> <!-- end .flash-message -->
		</div>
	</div>
    <div class="row"> 	 
		<div class="col-md-12 orderContainer">
			{!! Form::open(['route'=>'transit.store','method'=>'post','class'=>'orderForm']) !!}
				<div class="col-md-12 productList" >
					<div class="form-group col-md-6">
						<label for="">المخزن المحول منه</label>

						<select id="from_store_id" name="from_store_id[]" class="form-control storeName" required="required">
						<option value="">إختر المخزن المحول منه</option>
							@foreach($stores as $s)
								<option value="{{$s->id}}">{{$s->address}}</option>
							@endforeach
						</select>
						
					</div>

					<div class="form-group col-md-6">
						<label for="">المخزن المحول اليه</label>
						<select name="to_store_id[]" class="form-control" required="required">
							<option value="">إختر المخزن المحول اليه</option>
							@foreach($stores as $s)
								<option value="{{$s->id}}">{{$s->address}}</option>
							@endforeach
						</select>
						
					</div>
				  	<div class="form-group col-md-7">
						<label for="">الصنف</label>
						<input value="" name="product_id[]" autocomplete="off" class="typeahead form-control" required=""  type="text">
						
					</div>
					  <div class="col-md-5">
					  	<div class="form-group"> 
							<label for="">{{trans('app.Qantity')}} <span class="avilableQty"></span></label>
							<div class="input-group">
							  <input value="1" min="0" type="number" placeholder="{{ trans('app.Qantity') }}" type="text" required="required" name="qty[]"  class="form-control qty" >  
							  <span class="input-group-addon">
							  	@include('unit.dropdown',['selId'=>'','allunit'=>$units])
							  </span>
							</div>
						</div>
					  </div>
				  <div class="btnx">
				  		<a class="btn btn-primary addnewproducts btn-sm" href="#" role="button">+</a>
				  </div>
				</div>
				
				<div class="col-md-12">
				<button type="submit" class="btn btn-primary">{{trans('app.Submit')}}</button>
				</div>
			{!! Form::close() !!}
		</div>
	</div>
	<div style="display: none;" class="row cloneDiv">

		<div class="col-md-12 productList" >
			<div class="form-group col-md-6">
				<label for="">المخزن المحول منه</label>

				<select id="from_store_id" name="from_store_id[]" class="form-control storeName" required="required">
				<option value="">إختر المخزن المحول منه</option>
					@foreach($stores as $s)
						<option value="{{$s->id}}">{{$s->address}}</option>
					@endforeach
				</select>
				
			</div>

			<div class="form-group col-md-6">
				<label for="">المخزن المحول اليه</label>
				<select name="to_store_id[]" class="form-control" required="required">
					<option value="">إختر المخزن المحول اليه</option>
					@foreach($stores as $s)
						<option value="{{$s->id}}">{{$s->address}}</option>
					@endforeach
				</select>
				
			</div>
		  	<div class="form-group col-md-7">
				<label for="">الصنف</label>
				<input value="" name="product_id[]" autocomplete="off" class="typeahead form-control" required=""  type="text">
				
			</div>
			  <div class="col-md-5">
			  	<div class="form-group"> 
					<label for="">{{trans('app.Qantity')}} <span class="avilableQty"></span></label>
					<div class="input-group">
					  <input value="1" min="0" type="number" placeholder="{{ trans('app.Qantity') }}" type="text" required="required" name="qty[]"  class="form-control qty" >  
					  <span class="input-group-addon">
					  	@include('unit.dropdown',['selId'=>'','allunit'=>$units])
					  </span>
					</div>
				</div>
			  </div>
		  <div class="btnx">
				<a class="btn btn-danger btn-sm"  href="#" role="button">-</a>
	  			<a class="btn btn-primary addnewproducts btn-sm" href="#" role="button">+</a>
		  </div>
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
	$(document).on("click",".addnewproducts",function(e){
		e.preventDefault();
		var clone = $('.cloneDiv').html();
		$("div.productList:visible:last").after(clone);
		initTypeahead();
	});
});

function initTypeahead(){
 var path = "{{ route('autocomplete') }}";
 $('input.typeahead').typeahead({
        source:  function (query, process) {
          var store = $(this.$element).closest('div.productList').find('.storeName').val();
        return $.get(path, { query: query,store_id: store }, function (data) {
          
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
          elm.parents('div.productList').find('.unitclass').attr('price',item.price);
          elm.parents('div.productList').find('.unitclass').attr('price2',item.price2);
          elm.parents('div.productList').find('.unitclass').attr('price3',item.price3);
          var unitid = item.unitid.split(",");
          var title = item.title.split(",");
          var cost = item.cost_price.split(",");
          var price = item.price.split(",");
          var price2 = item.price2.split(",");
          var price3 = item.price3.split(",");
          elm.parents('div.productList').find('.unitclass').html("");
          option = "";
          storUnitName = "";
          for(i=0;i<unitid.length;i++){
            if(item.storeID==unitid[i]){
              storUnitName = title[i];
            }
            option += "<option price='"+price[i]+"' price2='"+price2[i]+"' price3='"+price3[i]+"' rel='"+cost[i]+"' value = '"+unitid[i]+"'>"+title[i]+"</option>"
          }
          elm.parents('div.productList').find('.unitclass').html(option);
          $(".unitclass").trigger("change");
          var qty = parseFloat(item.quantity).toFixed(2);
          if(isInt(item.quantity)){
            qty = item.quantity;
          }
          elm.closest('div.productList').find('.avilableQty').text("متاح:"+qty+" "+storUnitName);
          /*elm.closest('div.productList').find('input.originalprice').val(item.cost);
          elm.closest('div.productList').find('input.price').val(price);
          $(".price").trigger('input');*/
        },
    });

}

function isInt(value) {
  return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseFloat(value))
}
</script>
@stop()