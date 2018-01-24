@extends('layouts.app')
@section('content')
<div class="container">
	@if(!empty($list))
    <div class="row">
        <div class="col-md-12">

       		<div class="form-group pull-left">
			    <a class="btn btn-success print-window" href="#" role="button">{{ trans('app.Print') }}</a>
			</div>
			<table class="table table-hover table-bordered">
				<thead>
					<tr> 
						<td colspan="5">بيانات {{$storeName}}</td>
					</tr>
					<tr>
						<th>{{trans('app.ID')}}</th> 
						<th>المنتج</th>
						<th>الكمية</th>
						<th>سعر التكلفة</th>
						<th>اﻷجمالى</th>
					</tr>
				</thead>
				<tbody>
					@php 
						$total = 0;
					@endphp
					@foreach($list as $k=>$item)
						@php
							if($first){
								$cost = ($item->quantity-$item->sale_count)*$item->cost;
							}else{
								$cost = $item->qty*$item->product->cost;		
							} 
							$total +=$cost;
						@endphp
						<tr class="warning">
							<td> {{ $k+1 }} </td>
							<td> {{ ($first)?$item->title:$item->product->title }} </td>
							<td> {{ ($first)?$item->quantity-$item->sale_count:$item->qty }} </td>
							<td> {{ ($first)?$item->cost:$item->product->cost }} </td>
							<td> {{ $cost }} </td>
						</tr>
					@endforeach
				</tbody> 
			    <tfoot>
			        <tr class="info">
			        	<td colspan="2">المجموع</td>
			        	<td>{{($first)?$list->sum('quantity'):$list->sum('qty')}}</td>
			        	<td></td>
			        	<td>{{$total}}</td>
			        </tr>
			    </tfoot>
			</table>	
		</div>
	</div>
	@endif
</div>
@stop()