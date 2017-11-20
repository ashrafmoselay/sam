<style type="text/css">
	.checknew{
    	font-size: 12px;
    	padding: 5px;
    	position: absolute;
    	right: -10px;
    	top: 5px;
	}
	.checknew input{
    	margin-top: 10px;
	}
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
		margin-right: 5px;
	}
</style>
{!! Form::open($formAttr) !!}
	<div class="form-group col-md-3">
		<label for="">{{ trans('app.Supplier Name') }}</label>
		<button style="margin: -6px 4px;font-size: 12px;padding: 3px 10px 3px 10px" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">مورد جديد</span></button>
		<select data-show-subtext="true" data-live-search="true" name="supplier_id" id="clientsList"  class="form-control selectpicker" required="required">
		@foreach(\App\Suppliers::get() as $supplier)
			<?php 
				if($item->supplier_id!=$supplier->id && $item->id) continue; 
			?>
			<option {{($item->supplier_id==$supplier->id)?'selected=""':''}}  value="{{$supplier->id}}">{{$supplier->name}} {{"( رصيد المورد $supplier->due ج.م )"}}</option>
		@endforeach
		</select>
	</div>
	<?php 
		$raw = \App\PurchaseInvoice::orderBy('id', 'desc')->first();
		$rawid = 1;
		if($item->id){
			$rawid = $item->id;
		}elseif(count($raw)){
			$rawid = $raw->id + 1;
		}
	?>
	<div class="form-group col-md-3">
		<label for="">رقم الفاتورة</label>
		<input  name="id" value="{{$rawid}}"  type="text" class="form-control"  required="required" placeholder="رقم الفاتورة">
	</div>
	<?php 
		$stores = \App\Store::get();
	?>
	<div class="form-group col-md-3">
		<label for="">إختر المخزن</label>

		<select name="store_id" id="store_id" class="form-control" required="required">
			
			@foreach($stores as $s)
				<option {{($item->store_id==$s->id)?'selected=""':''}}   value="{{$s->id}}">{{$s->address}}</option>
			@endforeach
		</select>
		
	</div>
	<div class="form-group col-md-3">
		<label for="">التاريخ</label>
		<input  name="created_at" value="{{($item->id)?$item->created_at:date('Y-m-d')}}"  type="text" class="form-control datepicker"  required="required" placeholder="التاريخ">
	</div>
	<div id="orderList">
	@foreach($item->details as $key=>$prod)
		<div class="col-md-12 productList">
		  <div class="col-md-3">
		  	<div class="form-group">
				<label for="">{{ trans('app.Products') }} </label>
				<input value="{{$prod->product_id}}-{{$prod->product->title}}" name="product_id[]" class="typeahead form-control" required=""  type="text">
				
			</div>
		  </div>
		  <div class="col-md-3">
		  	<div class="form-group">
				<label for="">{{ trans('app.Cost Price') }}</label>
				<input value="{{$prod->cost}}"  name="cost[]" class="form-control cost" required=""  min="0" type="number" step="0.01" >
				
			</div>
		  </div>
		  <div class="col-md-3">
		  	<div class="form-group">
				<label for="">{{ trans('app.Qantity') }}<span class="avilableQty"></span></label>
				<div class="input-group">
				  <input value="{{$prod->qty}}" min="0" type="number" placeholder="{{ trans('app.Qantity') }}" type="text" required="required" name="quantity[]"  class="form-control qty" >
				  <span class="input-group-addon">
				  	@include('unit.dropdown',['selId'=>$prod->unit_id,'allunit'=>\App\Unit::get()])
				  </span>
				</div>
				
			</div>
		  </div>
		  <div class="col-md-3">
		  	<div class="form-group">
				<label for="">{{ trans('app.Total') }} </label>
				<input value="{{$prod->total}}"  name="totalcost[]" class="form-control total" required="" min="0" type="number" step="0.01" >
				
			</div>
		  </div>
		  <div class="btnx">
		  		@if($key>0)
				<a rel="{{$prod->id}}" class="btn btn-danger btn-sm" href="#" role="button">-</a>
				@else	
		  		<a class="btn btn-primary addnewproducts btn-sm" href="#" role="button">+</a>
				@endif
		  </div>
		</div>
	@endforeach
	@if(!$item->id)
	<div class="col-md-12 productList">
	  <div class="col-md-3">
	  	<div class="form-group">
			<label for="">{{ trans('app.Products') }}</label>
			<input name="product_id[]" autocomplete="off" class="typeahead form-control" required=""  type="text">
			
		</div>
	  </div>
		<div class="checknew form-group">
			جديد
			<input class="form-control isnew" type="checkbox" name="isnew[0]" class="newProd">
		</div>
	  <div class="col-md-3">
	  	<div class="form-group">
			<label for="">{{ trans('app.Cost Price') }}</label>
			<input name="cost[]" autocomplete="off" step="0.01" class="form-control cost originalprice" required=""  min="0" type="number" step="0.01" >
			
		</div>
	  </div>
	  <div class="col-md-3">
	  	<div class="form-group">
			<label for="">{{ trans('app.Qantity') }}<span class="avilableQty"></span></label>
			<div class="input-group">
			  <input value="1" min="0" type="number" placeholder="{{ trans('app.Qantity') }}" type="text" required="required" name="quantity[]"  class="form-control qty" >
			  <span class="input-group-addon">
			  	@include('unit.dropdown',['selId'=>'','allunit'=>\App\Unit::get()])
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
	  		<a class="btn btn-primary addnewproducts btn-sm" href="#" role="button">+</a>
	  </div>
	</div>
	@endif
	</div>
	<div class="form-group col-md-3">
		<label for="">{{ trans('app.Total') }}</label>
		<input name="total" readonly="" value="{{$item->total}}" min="0" type="number" step="0.01"  class="form-control totalCost"  placeholder="{{ trans('app.Total') }}">
		
	</div>
	<div class="form-group col-md-2">
		<label for="">الخصم</label>
		<input name="discount"  min="0" type="number" step="0.01" value="{{($item->discount)?$item->discount:0}}"  class="form-control discount"  placeholder="قيمة الخصم">
	</div>
	<div class="form-group col-md-2">
		<label for="">نسبة مئوية</label>
		<input class="form-control dis_type" type="checkbox" @if($item->discount_type==2) checked="" @endif name="discount_type">
		
	</div>
	<div class="form-group col-md-2">
		<label for="">{{trans('app.Paid')}}</label>
		<input name="paid" value="{{($item->paid)?$item->paid:0}}"  min="0" type="number" step="0.01" class="form-control paid" required="required"  placeholder="{{trans('app.Paid')}}">
	</div>
	<div class="form-group col-md-2 hide">
		<label for="">العرض</label>
		<input name="offer"  min="0" type="number" step="0.01" value="{{$item->offer}}"  class="form-control offer" placeholder="قيمة العرض">
	</div>
	<div class="form-group col-md-3">
		<label for="">{{trans('app.Due')}}</label>
		<input name="due" readonly="" type="number" step="0.01"  class="form-control due" value="{{$item->due}}"  placeholder="{{trans('app.Due')}}">
		
	</div>
	<div class="form-group col-md-12">
		<label for="">ملاحظات</label>
		<textarea rows="5" name="note" class="form-control">{{$item->note}}</textarea>
	</div>
	<div class="col-md-12">
	<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
	</div>
{!! Form::close() !!}