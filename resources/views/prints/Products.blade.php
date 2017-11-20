@include('prints/print')
<table   class="table table-bordered">
	<tr>
		<td>الرقم</td>
		<td>{{trans('app.Title')}}</td>
		<td>{{trans('app.Category Name')}}</td>
		<td>الفئة الفرعية</td>
		<td>{{trans('app.Cost Price')}}</td>
		<td>{{trans('app.Sale Price')}}</td>
		<td>{{trans('app.Avilable Qty')}}</td>
	</tr>
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
                	$avilbleUnit .= $u->avilablqty . " " .$u->title."<br/>";
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
		</tr>
	@endforeach
</table>