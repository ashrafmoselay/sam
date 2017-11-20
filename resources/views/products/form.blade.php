<style type="text/css">
	.boxX{    
		border-top: 2px solid #d0d0d0;
    	margin-bottom: 15px;
	    background-color: #f7f7f7;
	    position: relative;
	}
	.btnx{
	    position: absolute;
	    top: 0;
	    left: 0;
	}
	.withborder {
		
	}
	.cloneDiv{
		border-top: 2px solid #d0d0d0;
	}
	.unitItem{
		padding: 15px;
	}
	span.select2-selection.select2-selection--single {
    	display: none;
	}
</style>
@php
$allunit = \App\Unit::get();
@endphp
<div class="row">
    <div class="col-xs-12">
		@if(count($errors) >0)
		<ul class="alert alert-danger">
		@foreach($errors->all() as $err)
		<li>{{$err}}</li>
		@endforeach
		</ul>
		@endif
	</div>
</div>
<div class="row">
    <div class="col-md-12">
		{!! Form::open($formAttr) !!}
			<div class="form-group col-md-4 ">
				<label for="">أسم الصنف</label>
				<input name="title" type="text" value="{{$item->title}}" class="form-control" required="required" placeholder="{{ trans('app.Title') }}">
				
			</div>
			<div class="form-group col-md-4">
				<label for="">{{trans('app.Category Name')}}</label>
				<select required="required" name="category_id"  class="form-control">
					@foreach(\App\Category::where('type',1)->get() as $cat)
						<option {{($item->category_id==$cat->id)?'selected=""':''}}  value="{{$cat->id}}">{{$cat->name}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-md-4">
				<label for="">الفئة الفرعية</label>
				<select name="category2_id"  class="form-control">
					<option value="">إختر الفئة الفرعية</option>
					@foreach(\App\Category::where('type',2)->get() as $cat)
						<option {{($item->category2_id==$cat->id)?'selected=""':''}}  value="{{$cat->id}}">{{$cat->name}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-md-12 {{ (Config::get('custom-setting.use_barcode')==2)?'hide':''}}">
				<label for="">{{ trans('app.code') }}</label>
				<input style="width: 100%;" name="code" type="text" value="{{($item->code)?$item->code:str_random(10).rand(1,9)}}" class="form-control tag-input"  placeholder="{{ trans('app.code') }}">
			</div>
			<div class="form-group col-md-6">
				<label for="">موديل الصنف</label>
				<input name="model" value="{{$item->model}}" type="text" class="form-control" placeholder="موديل الصنف">
			</div>
			<div class="form-group col-md-6">
				<label for="">حد الطلب</label>
				<input name="observe" min="0" value="{{($item->observe)?$item->observe:0}}" type="number" step="1" class="form-control" required="required" placeholder="حد الطلب">
			</div>
			<hr/>
			<div class="newitem">
				<div class="col-md-12 boxX">
					<div class="box">
						<div class="box-header">
							<h5>الوحدات</h5>
						</div>
						<!-- /.box-header -->
						<div id="unitList" class="box-body">
							  <div class="btnx">
						  			<a class="btn btn-primary addnewunit btn-sm" href="#" role="button">+</a>
							  </div>

							@if(count($item->unit))
							@foreach($item->unit as $vv=>$unitItem)
								@include('products.unit',['allunit'=>$allunit,'itemD'=>isset($itemData->unit)?$itemData->unit:'' ])
							@endforeach
							@else
								@include('products.unit',['first'=>true,'allunit'=>$allunit])
							@endif
						</div>
					</div>
				</div>
			</div>
			<?php /*
			<hr/>
			<div class="row productCostsPrice {{(count($item->unit))?'hide':''}}">
				<div class="form-group col-md-3">
					<label for="">{{ trans('app.Cost Price') }}</label>
					<input name="cost" value="{{$item->cost}}" min="0" type="number" step="0.01"  class="form-control" required="required" placeholder="{{ trans('app.Cost Price') }}">
					
				</div>
				<div class="form-group col-md-3">
					<label for="">سعر البيع القطاعى</label>
					<input name="price" min="0" type="number" step="0.01"  class="form-control" value="{{($item->price)?$item->price:0}}" required="required"   placeholder="سعر البيع القطاعى">
					
				</div>
				<div class="form-group col-md-3">
					<label for="">سعر البيع الجملة</label>
					<input name="price2" min="0" type="number" step="0.01"  class="form-control" value="{{$item->price2}}"   placeholder="سعر البيع الجملة">
					
				</div>
				<div class="form-group col-md-3">
					<label for="">سعر البيع جملة الجملة</label>
					<input name="price3" min="0" type="number" step="0.01"  class="form-control" value="{{$item->price3}}"  placeholder="سعر البيع جملة الجملة">
					
				</div>
			</div>*/?>
			<?php 
				$stores = \App\Store::get();
			?>
			<div class="Itemstores">
				@foreach($stores as $s)
				<?php 
					$store = \App\ProductsStore::where('product_id',$item->id)->where('store_id',$s->id)->first();
						$qty = 0;
						if($store)
						$qty = $store->qty - $store->sale_count;
					
				?>
				<div class="form-group col-md-6 withborder">
					<label for="">المخزن</label>
	  				<input type="hidden" class="form-control" name="store[]"  value="{{$s->id}}" >
	  				<input type="text" disabled="" class="form-control" value="{{$s->address}}" >
				</div>
				
				<div class="form-group col-md-6 withborder">
					<label for="">{{ trans('app.Qantity') }}</label>
					<div class="input-group">
					<input name="qty[]" value="{{$qty}}" type="number" step="1"  class="form-control" required="required" placeholder="{{ trans('app.Qantity') }}">
					  <span class="input-group-addon">
					  	@include('unit.dropdown',['selId'=>(isset($store->unit_id))?$store->unit_id:'','allunit'=>$allunit])
					  </span>
					</div>
					
				</div>
				@endforeach
			</div>
			<div class="form-group col-md-12">
				<label for="">{{ trans('app.Short Description') }}</label>
				<textarea class="form-control" name="description">{{$item->description}}</textarea>
			</div>
			<div class="col-md-12">
				<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
			</div>
		{!! Form::close() !!}
	</div>
</div>
@section('style')
 <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/mab-jquery-taginput.css")}}">
@endsection
@section('javascript')
 <script src="{{ asset("/bower_components/AdminLTE/mab-jquery-taginput.js")}}"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".barcode").trigger("change");
	$('.tag-input').tagInput();

	$(document).on("click",".btn-danger",function(e){
		e.preventDefault();
		$(this).closest('div').parent('div').remove();
		if($('.btn-danger:visible').length==0){
			$(".sstorUnit").hide();
			//$(".productCostsPrice").show();
		}
	});
	$(document).on("click",".addnewunit",function(e){
		e.preventDefault();
		//$(".productCostsPrice").hide();
		var clone = $('.cloneDiv:hidden').clone();
		var i = $('.btn-danger:visible').length;
		clone.find('input.defsale').attr('name','default_sale['+i+']');
		clone.find('input.defpur').attr('name','default_purchase['+i+']');
		clone.show();
		$(".sstorUnit").show();
		$("#unitList").append(clone);
		getSelectionUnit();
	});
	$(document).on("change",".barcode",function(e){
		e.preventDefault();
		var input = $(this);
		$(".help-block").remove();
		input.parent("div").removeClass("has-error");
		var barcode = input.val();
		var arabic = /[\u0600-\u06FF\u0750-\u077F]/;
		if(arabic.test(barcode)){
			input.parent("div").addClass("has-error");
			input.after('<div class="help-block">الباركود لابد ان لا يحتوى على حروف عربية </div>');
			return false;
		}
		var url_ = "{{url('products/checkCode')}}";
		$.ajax({
			url:url_,
			type:'GET',
			data:{barcode:barcode},
			success:function(found){
				if(found){
					input.parent("div").addClass("has-error");
					input.after('<div class="help-block">هذا الكود موجود بالفعل</div>');
				}
			}
		});
	});
	$(document).on("change","#unitList select",function(e){
		getSelectionUnit();
	});
	getSelectionUnit();
});
function getSelectionUnit(){
	
	var optins = "";
	$("#unitList select").each(function() {
		var txt = $(this).find("option:selected").text();
		var val = $(this).find("option:selected").val();
		optins+= "<option value='"+val+"'>"+txt+"</option>";
	});
	$(".Itemstores select").each(function() {
		var selected = $(this).attr('selUnit');
		$(this).html("");
		$(this).html(optins);
		$(this).val(selected);
	});
}
</script>
@stop()