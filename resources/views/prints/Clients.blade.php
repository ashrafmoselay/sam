@include('prints/print')
<table class="table table-bordered">
	
		<tr>
			<th>الرقم</th>
			<th>{{trans('app.Client Name')}}</th>
			<th>{{trans('app.qest_value')}}</th>
			<th>{{trans('app.Mobile')}}</th>
			<th>{{trans('app.Total')}}</th>
			<th>{{trans('app.Paid')}}</th>
			<th>{{trans('app.Due')}}</th>
		</tr>
	
		@foreach($list as $item)
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->name }} </td>
				<td> {{ $item->qest_value }} </td>
				<td> {{ $item->mobile }} </td>
				<td> {{ $item->total }} </td>
				<td> {{ $item->paid }} </td>
				<td> {{ $item->due }} </td>
			</tr>
		@endforeach
	
		<tr class="info">
			<td colspan="4">{{trans('app.Total')}}</td>
			<td>{{$list->sum('total')}}</td>
			<td>{{$list->sum('paid')}}</td>
			<td>{{$list->sum('due')}}</td>
		</tr>
	
</table>