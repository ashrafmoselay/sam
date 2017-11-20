
<table class="table table-hover table-bordered">
	<thead>
		<tr>
			<td colspan="7">الموردين</td>
		</tr>
		<tr>
			<th>@sortablelink('id',trans('app.ID'))</th>
			<th>@sortablelink('name',trans('app.Supplier Name'))</th>
			<th>@sortablelink('mobile',trans('app.Mobile'))</th>
			<th>@sortablelink('total',trans('app.Total'))</th>
			<th>@sortablelink('paid',trans('app.Paid'))</th>
			<th>@sortablelink('due',trans('app.Due'))</th>
			<th>{{ trans('app.action') }}</th>
		</tr>
	</thead> 
	<tbody>
		@foreach($list as $item)
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->name }} </td>
				<td> {{ $item->mobile }} </td>
				<td> {{ $item->total }} </td>
				<td> {{ $item->paid }} </td>
				<td> {{ $item->due }} </td>
				<td>
				<a class="btn btn-primary" href="suppliers/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a> 
				@if($item->due!=0)
				<a class="btn btn-success" href="suppliers/pay/{{ $item->id }}" role="button">{{ trans('app.pay') }}</a>
				@endif
				<a class="btn btn-default" href="suppliers/{{ $item->id }}" role="button">كشف حساب</a>
				<a class="btn btn-danger" href="suppliers/destroy/{{ $item->id }}" role="button">{{ trans('app.Delete') }}</a>

				</td>
			</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr class="danger">
			<td colspan="3">{{trans('app.Total')}}</td>
			<td>{{$list->sum('total')}}</td>
			<td>{{$list->sum('paid')}}</td>
			<td>{{$list->sum('due')}}</td>
			<td></td>
		</tr>
	</tfoot>
</table>
 <div class="row text-center">
 	{!! $list->appends(\Request::except('page'))->render() !!}
 </div>