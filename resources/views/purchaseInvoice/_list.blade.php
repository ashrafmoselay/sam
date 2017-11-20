
<table class="table table-hover table-bordered">
	<thead>
		<tr>
			<td colspan="11">المشتريات</td>
		</tr>
		<tr>
			<th></th>
			<th>@sortablelink('id',trans('app.ID'))</th>
			<th>@sortablelink('supplier_id',trans('app.Supplier Name'))</th>
			<th>@sortablelink('total',trans('app.Total'))</th>
			<th>@sortablelink('discount','قيمة الخصم')</th>
			<th>@sortablelink('offer','قيمة العرض')</th>

			<th>@sortablelink('paid',trans('app.Paid'))</th>
			<th>@sortablelink('due',trans('app.Due'))</th>
			<th>العدد</th>
			<th>@sortablelink('created_at',trans('app.Created'))</th>
			<th>{{ trans('app.action') }}</th>
		</tr>
	</thead>
	<tbody>
		@php 
		$totaldiscount = 0;
		@endphp
		@foreach($list as $i=>$item)
			<tr>
				<td> {{ $i+1 }} </td>
				<td> {{ $item->id }} </td>
				<td> {{ $item->supplier->name }} </td>
				<td> {{ $item->total }} </td>
				<td> 
				@php
					$discount=$item->discount;
                    $dist = $item->discount.' ج.م';
                    if($item->discount){
                        if($item->discount_type==2){
                            $discount=$item->total*($item->discount/100);
                            $dist = "%".$item->discount. " ( $discount ج.م )";
                        }
                    }
                    $totaldiscount += $discount;
                @endphp
                {{$dist}} </td>
				<td> {{ $item->offer }} </td>
				<td> {{ $item->paid }} </td>
				<td> {{ $item->due }} </td> 
				<td> {{ $item->totalQty }} </td> 
				<td> {{ $item->created_at }} </td>
				<td>
				<a class="btn btn-success" href="purchaseInvoice/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a>
				<a class="btn btn-primary" href="purchaseInvoice/{{ $item->id }}" role="button">{{ trans('app.Show') }}</a>
				</td>
			</tr>
		@endforeach
	</tbody>
    <tfoot>
    <?php 
    	$total = $list->sum('total');
    	$discount = $totaldiscount;
    	$offer = $list->sum('offer');
    	$final = $total - $discount - $offer;
    ?>
        <tr class="danger">
        	<td colspan="3">المجموع</td>
        	<td>{{$total}}</td>
        	<td>{{$discount}}</td>
        	<td>{{$offer}}</td>
        	<td>{{$list->sum('paid')}}</td>
        	<td>{{$list->sum('due')}}</td>
        	<td>{{$list->sum('totalQty')}}</td>
        	<td colspan="2"></td>
        </tr>
        <tr class="warning">
        	<td colspan="3">إجمالى بعد الخصم والعرض</td>
        	<td style="text-align: center;" colspan="3">{{$final}}</td>
        	<td colspan="5"></td>
        </tr>
    </tfoot>
</table>
 <div class="row text-center">
 	{!! $list->appends(\Request::except('page'))->render() !!}
 </div>