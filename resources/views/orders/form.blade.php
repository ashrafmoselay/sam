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
    <div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))

      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
  </div>
{!! Form::open($formAttr) !!}
	<div class="form-group col-md-3">
		<label for="">{{trans('app.Client Name')}}</label>
		<button style="margin: -6px 4px;font-size: 12px;padding: 3px 10px 3px 10px" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">عميل جديد</span></button>
		<select data-show-subtext="true" data-live-search="true"  id="clientsList" name="client_id"  class="form-control selectpicker" required="required">
		<option value="">إختر العميل</option>
		@foreach(\App\Clients::get() as $client)
			<?php 
				if($item->client_id!=$client->id && $item->id) continue; 
			?>
			<option balance="{{$client->due}}" rel="{{$client->type}}" {{($item->client_id==$client->id)?'selected=""':''}}  value="{{$client->id}}">{{$client->name}} {{"( رصيد العميل  $client->due ج.م )"}}</option>
		@endforeach
		</select>
	</div>
	<?php 
		$raw = \App\Orders::orderBy('id', 'desc')->first();
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
	<div class="form-group col-md-3">
		<label for="">التاريخ</label>
		<input  name="created_at" value="{{($item->id)?$item->created_at:date('Y-m-d')}}"  type="text" class="form-control datepicker"  required="required" placeholder="التاريخ">
	</div>
	<div class="form-group col-md-3">
		<label for="">{{trans('app.Payment Type')}}</label>
		<select name="payment_type"  class="form-control" required="required">
		<option {{($item->payment_type==1)?'selected=""':''}}  value="1">{{trans('app.Cash Payment')}}</option>
		<option {{($item->payment_type==2)?'selected=""':''}} value="2">{{trans('app.Payment in installments')}}</option>
		
		</select>
	</div>
	@foreach($item->details as $key=>$prod)
		<div class="col-md-12 productList">
			<?php 
				$stores = \App\Store::get();
			?>
			<div class="form-group col-md-2">
				<label for="">إختر المخزن</label>
				<select name="store_id[]" class="form-control storeName" required="required">
					@foreach($stores as $s)
						<option @if($prod->store_id==$s->id) selected="" @endif value="{{$s->id}}">{{$s->address}}</option>
					@endforeach
				</select>
				
			</div>
		  <div class="col-md-2">
		  	<div class="form-group">
				<label for="">الصنف </label>
				<input value="{{$prod->product_id}}-{{$prod->product->title}}" name="product_id[]" autocomplete="off" class="typeahead form-control" required=""  type="text">
				
			</div>
		  </div>
		  <div class="col-md-2">
		  	<div class="form-group">
				<label for="">{{trans('app.Qantity')}}<span class="avilableQty"></span> </label>
				<div class="input-group">
				  <input value="{{$prod->qty}}" min="0" type="number" placeholder="{{ trans('app.Qantity') }}" type="text" required="required" name="quantity[]"  class="form-control qty" > 
				  <span class="input-group-addon">
				  	@include('unit.dropdown',['selId'=>$prod->unit_id,'allunit'=>\App\Unit::get()])
				  </span>
				</div>
			</div>
		  </div>
		  <div class="col-md-2 {{(Config::get('custom-setting.show_cost_price')==2)?'hide':''}}">
		  	<div class="form-group">
				<label for="">{{trans('app.Cost Price')}} </label>
				<input value="{{$prod->cost}}" name="cost[]"  readonly="" class="form-control originalprice" required="" type="text">
			</div>
		  </div>
		  <div class="col-md-2">
		  	<div class="form-group">
				<label for="">{{trans('app.Sale Price')}}</label>
				<input value="{{$prod->price}}" name="price[]" class="form-control price" required=""  min="0" type="number" step="0.01">
			</div>
		  </div>
		  <div class="col-md-2">
		  	<div class="form-group">
				<label for="">{{trans('app.Total')}} </label>
				<input readonly="" value="{{$prod->total}}" name="totalcost[]" class="form-control total" required="" min="0" type="number" step="0.01">
				
			</div>
		  </div>
		  <div class="btnx">
		  		@if( $key>0)
				<a rel="{{$prod->id}}" class="btn btn-danger btn-sm" href="#" role="button">-</a>
				@endif
				<a class="btn btn-primary addnewproducts btn-sm" href="#" role="button">+</a>
		  </div>
		</div>
	@endforeach
	@if(!$item->id)
		<div class="col-md-12 productList">

		<?php 
			$stores = \App\Store::get();
		?>
		<div class="form-group col-md-2">
			<label for="">منفذ البيع</label>
			<select name="store_id[]" class="form-control storeName" required="required">
				@foreach($stores as $s)
					<option value="{{$s->id}}">{{$s->address}}</option>
				@endforeach
			</select>
			
		</div>
		  <div class="col-md-2">
		  	<div class="form-group">
				<label for="">الصنف </label>
				<input value="" name="product_id[]" autocomplete="off" class="typeahead form-control" required=""  type="text">
				
			</div>
		  </div>
		  <div class="col-md-2">
		  	<div class="form-group"> 
				<label for="">{{trans('app.Qantity')}} <span class="avilableQty"></span></label>
				<div class="input-group">
				  <input value="1" min="0" type="number" placeholder="{{ trans('app.Qantity') }}" type="text" required="required" name="quantity[]"  class="form-control qty" >  
				  <span class="input-group-addon">
				  	@include('unit.dropdown',['selId'=>'','allunit'=>\App\Unit::get()])
				  </span>
				</div>
			</div>
		  </div>
		  <div class="col-md-2 {{(Config::get('custom-setting.show_cost_price')==2)?'hide':''}}">
		  	<div class="form-group">
				<label for="">شراء </label>
				<input readonly="" name="cost[]" class="form-control originalprice" required="" type="text">
			</div>
		  </div> 
		  <div class="col-md-2">
		  	<div class="form-group">
				<label for="">بيع </label>
				<input name="price[]" autocomplete="off" class="form-control price" required=""  min="0" type="number" step="0.01">
			
			</div>
		  </div>
		  <div class="col-md-2">
		  	<div class="form-group">
				<label for="">{{trans('app.Total')}} </label>
				<input readonly="" name="totalcost[]"  class="form-control total" required="" min="0" type="number" step="0.01">
				
			</div>
		  </div>
		  <div class="btnx">
		  		<a class="btn btn-primary addnewproducts btn-sm" href="#" role="button">+</a>
		  </div>
		</div>
	@endif
	<div class="form-group col-md-3">
		<label for="">{{trans('app.Total')}}</label>
		<input name="total" value="{{$item->total}}" readonly="" min="0" type="number" step="0.01" class="form-control totalCost"  placeholder="{{trans('app.Total')}}">
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
	<div class="form-group col-md-3">
		<label for="">{{trans('app.Due')}}</label>
		<input name="due" value="{{$item->due}}"  readonly="" type="number" step="0.01" class="form-control due"  placeholder="{{trans('app.Due')}}">
	</div>
	@if(!$item->id)
	<div class="form-group col-md-6">
		<label for="">رصيد العميل السابق</label>
		<input  style="background-color: #d2ff7e;" name="oldb" value=""  disabled="" type="number" step="0.01" class="form-control oldbalnace"  placeholder="">
	</div>
	<div class="form-group col-md-6">
		<label for="">إجمالى رصيد العميل</label>
		<input style="background-color: #d2ff7e;" name="newb" value=""  disabled="" type="number" step="0.01" class="form-control newbalance"  placeholder="">
	</div>
	@endif
	<div class="form-group col-md-12">
		<label for="">ملاحظات</label>
		<textarea rows="5" name="note" class="form-control">{{$item->note}}</textarea>
	</div>
	<div class="col-md-12">
	<button type="submit" class="btn btn-primary submitform">{{trans('app.Submit')}}</button>
	<a href="#"  class="btn btn-success profit">قيمة الربح</a>
	</div>
{!! Form::close() !!}