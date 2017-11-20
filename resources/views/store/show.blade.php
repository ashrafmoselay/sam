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
						<td colspan="6">بيانات {{$storeName}}</td>
					</tr>
					<tr>
						<th>{{trans('app.ID')}}</th> 
						<th>@sortablelink('ptitle','المنتج')</th>
						<th>@sortablelink('qty','الكمية')</th>
						<th>@sortablelink('unit_id','الوحدة')</th>
						<th>@sortablelink('cost','سعر التكلفة')</th>
						<th>اﻷجمالى</th>
					</tr>
				</thead>
				<tbody>
					@php 
						$total = 0;
					@endphp
					@foreach($list as $k=>$item)
						@php
							//$defaultCost =  $item->product->cost;
							$unit = \App\ProductStoreUnit::where('product_id',$item->product_id)->where('unit_id',$item->unit_id)->first();
							//if($unit){
								$defaultCost = $unit->cost_price;
								//dd($defaultCost) ;
							//}
							$cost = ($item->qty - $item->sale_count ) * $defaultCost;		
							
							$total +=$cost;
						@endphp
						<tr class="warning">
							<td> {{ $k+1 }} </td>
							<td> {{ $item->product->title }} </td>
							<td> {{ $item->product->decToFraction($item->qty-$item->sale_count) }} </td>
							<td>{{isset($item->unit->title)?$item->unit->title:'عدد'}}</td>
							<td> {{ round($defaultCost,2) }} </td>
							<td> {{ round($cost,2) }} </td>
						</tr>
					@endforeach
				</tbody> 
			    <tfoot>
			        <tr class="info">
			        	<td colspan="2">المجموع</td>
			        	<td>{{$list->sum('qty')}}</td>
			        	<td></td>
			        	<td></td>
			        	<td>{{round($total,2)}}</td>
			        </tr>
			    </tfoot>
			</table>	
		</div>
	</div>
	@endif
</div>
@stop()