@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
	    <div class="flash-message">
	    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
	      @if(Session::has('alert-' . $msg))

	      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
	      @endif
	    @endforeach
	  </div> <!-- end .flash-message -->
        <div class="col-md-12">
       		<div class="form-group pull-right hideonprint">
			    <div class="col-md-6">
			    <input type="text" class="search form-control" placeholder="{{ trans('app.What you looking for?') }}">
				</div>
				<div class="col-md-6">
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
			    <a class="btn btn-success" href="clients/create" role="button">{{ trans('app.Create') }}</a>
			    <a target="_blank" class="btn btn-info" href="{{url('printPdf','Clients')}}" role="button">طباعة</a>
                <a class="btn btn-default print-window" href="#" role="button">
                {{ trans('app.Print') }}
                </a>
			</div>
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			<div id="list">
				{!! View::make('clients._list',compact('list'))!!}
			 </div>

		</div>
	</div>
</div>
@stop()


@section('javascript')
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click",".btn-danger",function(e){
			e.preventDefault();
			var btn = $(this);
			var url_ = btn.attr('href');
	   			swal({title: "هل أنت متأكد من هذه العمليه ؟ ",
		         text: "سوف تفقد البيانات ولن تستطيع استراجعها",
		         type: "warning",
		         showCancelButton: true,
		         confirmButtonColor: "#DD4140",
		         closeOnConfirm: false,
		         showLoaderOnConfirm: true,
			     cancelButtonText: "إلغاء",      
			     confirmButtonText: "نعم متأكد", 
		      },
		      function(){	
					$.ajax({
						url:url_,
						success:function(result){
							if(result.length){

							swal(
								{
								title:"خطأ!", text:"لا يمكنك حذف هذا العميل لانه يوجد فواتير مبيعات خاصه به",type:"error",confirmButtonText: "تمام",
								}
								);
							}else{
							btn.closest('tr').fadeOut();
							swal(
								{
								title:"تم الحذف!", text:"تمت عملية الحذف بنجاح",type:"success",confirmButtonText: "تمام",
								}
								);
							}
						}
					});
		      });
		});
		$(document).on("input",".search",function(e){ 
			e.preventDefault(); 
			var url_ = "{{url('clients/search')}}";
			if($('.search').val())
				url_ = "{{url('clients/search')}}/"+$('.search').val();
			$.ajax({
				url:url_,
				type:'GET',
				success:function(result){
					$("#list").html(result);
				}
			});
		});
		$(document).on("change",".page_size",function(e){
			e.preventDefault();
			var page_size = $(".page_size").val();
			var url_ = "{{url('clients/search')}}";
			$.ajax({
				url:url_,
				data:{page_size:page_size},
				type:'GET',
				success:function(result){
					$("#list").html(result);
					$(".page_size").val(page_size);
				}
			});
		});
	});
</script>
@stop()