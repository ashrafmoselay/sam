@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
       		<div class="form-group pull-left">
			    <a class="btn btn-success" href="masrofat/create" role="button">{{ trans('app.Create') }}</a>
			    <a target="_blank" class="btn btn-info printpdf" href="{{url('printPdf','Masrofat')}}" role="button">طباعة</a>
			</div>
   			<div class="row pull-right">
				<div class="col-md-3">
	   				<div class="form-group">
					    <input type="text" class="search form-control" placeholder="{{ trans('app.What you looking for?') }}">
					</div>
				</div>
			  <div class="col-md-2">
			  	<div class="form-group ">
					<input type="text" class="fromdate form-control datepicker" placeholder="{{ trans('app.From Date') }}">
				</div>
			  </div>
			  <div class="col-md-2">
			  	<div class="form-group"> 
					<input type="text" class="todate form-control datepicker" placeholder="{{ trans('app.To Date') }}">
				</div>
			  </div>
			  	<div class="col-md-3">
						<select name="sharek_id"  class="form-control sharek_id">
						<option value="">{{trans('app.MasrofType')}}</option>
						<option value="g">{{trans('app.general')}}</option>
						@foreach(\App\Shoraka::get() as $shorka)
							<option value="{{$shorka->id}}">{{$shorka->name}}</option>
						@endforeach
						</select>
				</div>
				<div class="form-group col-md-2">
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
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			
			<div id="list">
				{!! View::make('masrofat._list',compact('list'))!!}
			 </div>
		</div>
	</div>
</div>
@stop()


@section('javascript')
<script type="text/javascript">
	$(document).ready(function(){
		$('.datepicker').datepicker({format: 'yyyy-mm-dd',rtl: true});
		$(document).on("click",".btn-danger",function(e){
			e.preventDefault();
			var btn = $(this);
			var url_ = btn.attr('href');
	   			swal({title: "هل أنت متأكد من هذه العمليه ؟ ",
		         text: "سوف تفقد البيانات ولن تستطيع استراجعها",
		         type: "warning",
		         showCancelButton: true,
		         confirmButtonColor: "#DD4140",
		         closeOnConfirm: true,
		         showLoaderOnConfirm: true,
			     cancelButtonText: "إلغاء",      
			     confirmButtonText: "نعم متأكد", 
		      },
		      function(){	
					$.ajax({
						url:url_,
						success:function(result){
							btn.closest('tr').fadeOut();
						}
					});
		      });
		});

		$(document).on("input",".search",function(e){
			e.preventDefault();
			var from = $(".fromdate").val();
			var to = $(".todate").val();
			var sharek_id = $(".sharek_id").val();
			var url_ = "{{url('masrofat/search')}}";
			if($('.search').val())
				url_ = "{{url('masrofat/search')}}/"+$('.search').val();
			$.ajax({
				url:url_,
				type:'GET',
				data:{from:from,to:to,sharek_id:sharek_id},
				success:function(result){
					$("#list").html(result);
				}
			});
		});
		$(document).on("change",".fromdate,.todate,.sharek_id",function(e){
			e.preventDefault();
			var from = $(".fromdate").val();
			var to = $(".todate").val();
			var sharek_id = $(".sharek_id").val();
			var url_ = "{{url('masrofat/search')}}";
			if($('.search').val())
				url_ = "{{url('masrofat/search')}}/"+$('.search').val();
			$.ajax({
				url:url_,
				type:'GET',
				data:{from:from,to:to,sharek_id:sharek_id},
				success:function(result){
					appendParm();
					$("#list").html(result);
				}
			});
		});
		$(document).on("change",".page_size",function(e){
			e.preventDefault();
			var page_size = $(".page_size").val();
			var from = $(".fromdate").val();
			var to = $(".todate").val();
			var sharek_id = $(".sharek_id").val();
			var url_ = "{{url('masrofat/search')}}";
			if($('.search').val())
				url_ = "{{url('masrofat/search')}}/"+$('.search').val();
			$.ajax({
				url:url_,
				type:'GET',
				data:{page_size:page_size,from:from,to:to,sharek_id:sharek_id},
				success:function(result){
					 appendParm();
					$("#list").html(result);
					$(".page_size").val(page_size);
				}
			});
		});
	});
	function appendParm(){
		var from = $(".fromdate").val();
		var to = $(".todate").val();
		var newhref = "{{url('printPdf','Masrofat')}}"+"?sharek_id="+$(".sharek_id").val()+"&from="+from+"&to="+to;
		$(".printpdf").attr('href',newhref);
	}
</script>
@stop()