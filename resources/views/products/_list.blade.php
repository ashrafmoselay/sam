<table  class="table table-hover table-bordered">
<thead>
	<tr>
		<td colspan="9">اﻷصناف</td>
	</tr>
	<tr>
		<th>@sortablelink('id',trans('app.ID'))</th>
		<th class="col-md-2">@sortablelink('title','الصنف')</th>
		<th class="col-md-1">@sortablelink('category_id','الفئة')</th>
		<th class="col-md-1">@sortablelink('category2_id','الفئة الفرعية')</th>
		<?php /*
		@if(Config::get('custom-setting.use_barcode')==1)
		<th class="col-md-1">@sortablelink('code',trans('app.code'))</th>
		@endif */ ?>
		<th class="{{(Config::get('custom-setting.show_cost_price')==2)?'hide':''}}">@sortablelink('cost',trans('app.Cost Price'))</th>
		<th>@sortablelink('price','سعر البيع')</th>
		<?php /*<th>@sortablelink('sale_count',trans('app.Sale Count'))</th>*/ ?>
		<th class="col-md-1">{{trans('app.Avilable Qty')}}</th>
		<th class="col-md-2">{{ trans('app.action') }}</th>
	</tr>
</thead>
<tbody>
	
	@foreach($list as $item)
		@php 
			$avilablqty = 0;
			$avilbleUnit = "";
	      	if(count($item->unit)){
	      	   $result = DB::table('products_store')
                ->leftJoin('unit','unit.id','products_store.unit_id')
	      	   	->where('products_store.product_id',$item->id)
                ->select(DB::raw('unit.title,sum(qty-sale_count) as avilablqty'))
                ->groupBy('products_store.unit_id')->get();
                foreach($result as $u){
                	$avilablqty += $u->avilablqty;
                	$avilbleUnit .= '<span style="text-align: center;direction: ltr;">'.$item->decToFraction($u->avilablqty) . "<span/> " .$u->title."<br/>";
                }
	      	}else{
	      		$avilablqty = \App\ProductsStore::where('product_id',$item->id)->sum(DB::raw('qty-sale_count'));
	      	}
			$check = $avilablqty <= Config::get('custom-setting.observe') || $avilablqty==$item->observe;
		@endphp
		<tr class="{{($check)?'danger':''}} ">
			<td> {{ $item->id }} </td>
			<td> {{ $item->title }} 
				<a href="#" class="itemdesc" title="{{ $item->title }}" rel="{{ $item->description }}" data-toggle="modal">
					<i class="fa fa-eye" aria-hidden="true"></i>
				</a>
			 </td>
			<td> {{ isset($item->category->name)?$item->category->name:'' }} </td>
			<td> {{ isset($item->category2->name)?$item->category2->name:'' }} </td>
			<?php /*
			@if(Config::get('custom-setting.use_barcode')==1)
			<td>الباركود<br/>
				<a href="#" title="{{ $item->title }}" class="savebarecode" rel="{{ $item->code }}" data-toggle="modal">عرض</a>
			 </td>
			@endif */ ?>

			<td class="{{(Config::get('custom-setting.show_cost_price')==2)?'hide':''}}">
				@php $price = ''; @endphp 
				@if(count($item->unit))
				@foreach($item->unit as $vv=>$unitItem)
					@if(count($unitItem->unit))
						{{ $unitItem->unit->title.":"}}
						@php $price .= $unitItem->unit->title.":"; @endphp
					@endif
					{{ $unitItem->cost_price }}<br/>
					@php $price .= $unitItem->sale_price."<br/>"; @endphp
				@endforeach
				@else
				{{ $item->cost }}
				@php $price = $item->price; @endphp 
				@endif
			</td>
			<td> {!! $price !!} </td>
			<td> {!! (count($item->unit))?$avilbleUnit:$avilablqty !!} </td>
			<td>
			<a class="btn btn-primary" href="products/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a> 
			<a class="btn btn-success" href="products/movement/{{ $item->id }}" role="button">{{ trans('app.movement') }}</a>
			@if(Config::get('custom-setting.use_barcode')==1)
			<a class="btn btn-warning" href="products/barcode/{{ $item->id }}" role="button">الباركود</a>
			@endif
			<?php /*
			<a class="btn btn-danger" href="products/destroy/{{ $item->id }}" role="button">{{ trans('app.Delete') }}</a> */ ?>

			</td>
		</tr>
	@endforeach
	<tfoot>
		<tr><td style="border:none;" colspan="9"></td></tr>
		<tr class="success">
			<td colspan="3">إجمالى تكلفة المنتجات المتاحة بالمخزن</td>
			<td colspan="6">{{round($totalRemainInStock,2)}}  ج.م</td>
		</tr>
	</tfoot>
</tbody>
</table>
@if(!isset($print))
<div class="row text-center">
	{!! $list->appends(\Request::except('page'))->render() !!}
</div>
@endif

<!-- Modal -->
<div  id="barcodeItem" class="modal fade" role="dialog">
  <div style="width: 50%;" class="modal-dialog">
    <!-- Modal content-->
    <div  class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">الباركود الخاص بالصنف </h4>
      </div>
      <div class="modal-body">
		
      </div>
     </div>
   </div>
</div>