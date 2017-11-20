@include('prints/print')
<table class="table table-bordered">
		<tr>
			<th>الرقم</th>
			<th>{{trans('app.Supplier Name')}}</th>
			<th>{{trans('app.Total')}}</th>
			<th>قيمة الخصم</th>
			<th>{{trans('app.Paid')}}</th>
			<th>{{trans('app.Due')}}</th>
			<th>{{trans('app.Created')}}</th>
		</tr>
		@foreach($list as $item)
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->supplier->name }} </td>
				<td> {{ $item->total }} </td>
				<td> {{ $item->discount }} </td>
				<td> {{ $item->paid }} </td>
				<td> {{ $item->due }} </td> 
				<td> {{ $item->created_at }} </td>
			</tr>
		@endforeach
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
        	<td>{{$list->sum('due')}}</td>
        	<td></td>
        </tr>
        <tr class="warning">
        	<td colspan="2">إجمالى بعد الخصم</td>
        	<td style="text-align: center;" colspan="2">{{$final}}</td>
        	<td colspan="4"></td>
        </tr>
</table>