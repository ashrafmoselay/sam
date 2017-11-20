@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
       		<div class="form-group pull-right hideonprint">
				<div class="col-md-5">
	   				<div class="form-group">
					    <input type="text" class="search form-control" placeholder="{{ trans('app.What you looking for?') }}">
					    <div style="margin: 10px;" class="loader">
					    	<h6>برجاء الإنتظار ...</h6>
					    	<img src="{{asset('/icon/loader.gif')}}">
					    </div>
					</div>
				</div>
   				<div class="form-group col-md-3">
					<select name="category_id" data-show-subtext="true" data-live-search="true"  class="form-control category_id selectpicker">
						<option value="">فئة رئيسية</option>
						<option {{(isset($_GET['category_id']) && $_GET['category_id']=="observe")?'selected=""':''}} value="observe">المنتجات التى بلغت حد الطلب</option>
						@foreach(\App\Category::where('type',1)->get() as $cat)
							<option {{(isset($_GET['category_id']) && $_GET['category_id']==$cat->id)?'selected=""':''}}   value="{{$cat->id}}">{{$cat->name}}</option>
						@endforeach
					</select>

				</div>
   				<div class="form-group col-md-3">
					<select name="category2_id" data-show-subtext="true" data-live-search="true"  class="form-control category2_id selectpicker">
						<option value=""> فئة فرعية</option>
						@foreach(\App\Category::where('type',2)->get() as $cat)
							<option {{(isset($_GET['category2_id']) && $_GET['category2_id']==$cat->id)?'selected=""':''}}   value="{{$cat->id}}">{{$cat->name}}</option>
						@endforeach
					</select>

				</div>
				<div class="form-group col-md-1">
				 	<select style="width: 80px;" class="form-control page_size"  name="page_size">
				 		<option value=""></option>
			 			<option  value="{{$list->total()}}">عرض الكل ( {{$list->total()}} ) </option>
				 		<option  value="20">20</option>
				 		@for($i=50;$i<=100;$i=$i+50)
				 		<option {{(isset($_GET['page_size']) && $_GET['page_size']==$i)?'selected=""':''}} value="{{$i}}">{{$i}}</option>
				 		@endfor
				 	</select>
				 </div>
			</div>
       		<div class="form-group pull-left hideonprint">
			    <a class="btn btn-success" href="products/create" role="button">{{ trans('app.Create') }}</a>
			    @if(Config::get('custom-setting.use_barcode')==1)
			    <a target="_blank" class="btn btn-primary" href="productlistCode" role="button">{{ trans('app.code') }}</a>
			    @endif
			    <a target="_blank" class="btn btn-info printpdf" href="{{url('printPdf','Products')}}" role="button">حفظ</a>
                <a class="btn btn-default print-window" href="#" role="button">
                {{ trans('app.Print') }}
                </a>
			</div>
			
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			<div id="list">
				@include('products._list')
			</div>
		</div>
	</div>
</div>
@stop()
@section('javascript')
<script type="text/javascript">
	$(document).ready(function(){
		$(".loader").hide();
		$(document).on("click",".savebarecode",function(e){
			e.preventDefault();
			var code = $(this).attr('rel');
			var title = $(this).attr('title');
			$("#barcodeItem").find(".modal-title").html("الباركود الخاص بالصنف "+title);
			$("#barcodeItem").find(".modal-body").html('<img src="data:image/png;base64,{{ DNS1D::getBarcodePNG('+code+', "C39+",2,33,array(1,1,1)) }}" alt="'+code+'"   />');
			$("#barcodeItem").modal('show');
		});
		$(document).on("click",".itemdesc",function(e){
			e.preventDefault();
			var desc = $(this).attr('rel');
			var title = $(this).attr('title');
			$("#barcodeItem").find(".modal-title").html("تفاصيل الصنف  "+title);
			$("#barcodeItem").find(".modal-body").html(desc);
			$("#barcodeItem").modal('show');
		});
		$(document).on("click",".btn-danger",function(e){
			e.preventDefault();
			if(!confirm('Are you sure you want to delete this item?')) return false;
			var btn = $(this);
			var url_ = btn.attr('href');
			$.ajax({
				url:url_,
				success:function(result){
					btn.closest('tr').fadeOut();
				}
			});
		});
		$(document).on("change",".search",function(e){
			e.preventDefault();
			//if (e.which == 13) {
				var url_ = "{{url('products/search')}}";
				if($('.search').val())
				url_ = "{{url('products/search')}}/"+$('.search').val();
				var category_id = $(".category_id").val();
				$.ajax({
					url:url_,
					type:'GET',
					data:{category_id:category_id},
					beforeSend:function(){
						$(".loader").show();
					},
					success:function(result){
						$(".loader").hide();
						$("#list").html(result);
					}
				});
			//}
		});

		$(document).on("change",".category_id,.category2_id",function(e){
			e.preventDefault();
			var category_id = $(".category_id").val();
			var category2_id = $(".category2_id").val();
			var url_ = "{{url('products/search')}}";
			if($('.search').val())
				url_ = "{{url('products/search')}}/"+$('.search').val();
			$.ajax({
				url:url_,
				type:'GET',
				data:{category_id:category_id,category2_id:category2_id},
					beforeSend:function(){
						$(".loader").show();
					},
					success:function(result){
						$(".loader").hide();
					 appendParm();
					$("#list").html(result);
				}
			});
		});
		$(document).on("change",".page_size",function(e){
			e.preventDefault();
			var page_size = $(".page_size").val();
			var category_id = $(".category_id").val();
			var category2_id = $(".category2_id").val();
			var url_ = "{{url('products/search')}}";
			if($('.search').val())
				url_ = "{{url('products/search')}}/"+$('.search').val();
			$.ajax({
				url:url_,
				type:'GET',
				data:{page_size:page_size,category_id:category_id,category2_id:category2_id},
				success:function(result){
					 appendParm();
					$("#list").html(result);
					$(".page_size").val(page_size);
				}
			});
		});
	});
	function appendParm(){
		var newhref = "{{url('printPdf','Products')}}"+"?category_id="+$(".category_id").val()+"&category2_id="+$(".category2_id").val()+"&q="+$('.search').val();
		$(".printpdf").attr('href',newhref);
	}
</script>
@stop()