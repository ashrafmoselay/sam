@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
       		<div class="form-group pull-left">
			    <a class="btn btn-success" href="category/create" role="button">{{ trans('app.Create') }}</a>
			</div>
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			
			<div id="list">
				{!! View::make('category._list',compact('list'))!!}
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
		         closeOnConfirm: true,
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
								title:"خطأ!", text:"لا يمكنك حذف هذه الفئه لانه تحتوى على أصناف",type:"error",confirmButtonText: "تمام",
								}
								);
							}else{
							btn.closest('tr').fadeOut();
							}
						}
					});
		      });
		});
	});
</script>
@stop()