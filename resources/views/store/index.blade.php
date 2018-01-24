@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
       		<div class="form-group pull-left">
			    <a class="btn btn-success" href="{{url('store/create')}}" role="button">{{ trans('app.Create') }}</a>
			</div>
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			<table class="table table-hover display">
				<thead>
					<tr>
						<th>@sortablelink('id',trans('app.ID'))</th>
						<th>@sortablelink('address','المخزن')</th>
						<th>@sortablelink('mobile','الموبيل')</th>
						<th>@sortablelink('note','ملاحظات')</th>
						<th>جرد المخزن</th>
						<th>{{ trans('app.action') }}</th>
					</tr>
				</thead>
				<tbody>
					@php
						$total = 0;
					@endphp
					@foreach($list as $key=>$item)
						<tr>
							<td> {{ $item->id }} </td>
							<td> {{ $item->address }} </td>
							<td> {{ $item->mobile }} </td>
							<td> {{ $item->note }} </td>
							<td>
								@php
				        		$query = DB::table('products')
				                ->join('products_store','product_id','products.id')
			                    ->where('store_id',$item->id)
				                ->select(DB::raw('sum((qty-sale_count)*products_store.cost)  as TotalCost'))->first();
	        					$totalRemainInStock = isset($query->TotalCost)?$query->TotalCost:0;
						        $total+=$totalRemainInStock;
								@endphp
								{{round($totalRemainInStock,2)}}
							</td>
							<td>
							<a class="btn btn-primary" href="store/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a>
							<a class="btn btn-warning" href="store/{{ $item->id }}" role="button">عرض</a>
							<a class="btn btn-danger" href="store/destroy/{{ $item->id }}" role="button">{{ trans('app.Delete') }}</a>

							</td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr class="danger">
						<td colspan="4">المجموع</td>
						<td>{{round($total,2)}}</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	 <div class="row text-center">
	 	{!! $list->appends(\Request::except('page'))->render() !!}
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
							if(result==1){

							swal(
								{
								title:"خطأ!", text:"لا يمكنك حذف هذا المخزن",type:"error",confirmButtonText: "تمام",
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
	});
</script>
@stop()