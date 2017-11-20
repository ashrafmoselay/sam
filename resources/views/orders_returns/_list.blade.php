
<table class="table table-hover table-bordered">
	<thead>
		<tr>
			<th>@sortablelink('id',trans('app.ID'))</th>
			<th>@sortablelink('supplier_id',trans('app.Supplier Name'))</th>
			<th>@sortablelink('total',trans('app.Total'))</th>
			<th>@sortablelink('discount','قيمة الخصم')</th>
			<th>@sortablelink('paid',trans('app.Paid'))</th>
			<th>@sortablelink('due',trans('app.Due'))</th>
			<th>@sortablelink('created_at',trans('app.Created'))</th>
			<th>{{ trans('app.action') }}</th>
		</tr>
	</thead>
	<tbody>
		@foreach($list as $item)
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->supplier->name }} </td>
				<td> {{ $item->total }} </td>
				<td> {{ $item->discount }} </td>
				<td> {{ $item->paid }} </td>
				<td> {{ $item->due }} </td> 
				<td> {{ $item->created_at }} </td>
				<td>
				<a class="btn btn-success" href="returns/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a>
				<a class="btn btn-primary" href="returns./{{ $item->id }}" role="button">{{ trans('app.Show') }}</a>
				</td>
			</tr>
		@endforeach
	</tbody>
    <tfoot>
    <?php 
    	$total = $list->sum('total');
    	$discount = $list->sum('discount');
    	$final = $total - $discount;
    ?>
        <tr class="danger">
        	<td colspan="2">المجموع</td>
        	<td>{{$total}}</td>
        	<td>{{$discount}}</td>
        	<td>{{$list->sum('paid')}}</td>
        	<td colspan="3">{{$list->sum('due')}}</td>
        </tr>
        <tr class="warning">
        	<td colspan="2">إجمالى بعد الخصم</td>
        	<td style="text-align: center;" colspan="2">{{$final}}</td>
        	<td colspan="4"></td>
        </tr>
    </tfoot>
</table>
 <div class="row text-center">
 	{!! $list->appends(\Request::except('page'))->render() !!}
 </div>