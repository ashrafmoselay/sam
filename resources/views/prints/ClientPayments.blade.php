@include('prints/print')
<table class="table table-hover table-bordered">
	<thead>
		<tr class="active">
			<th>{{ trans('app.ID') }}</th>
			<th>{{ trans('app.Client Name') }}</th>
			<th>{{ trans('app.Total') }}</th>
			<th>{{ trans('app.Paid') }}</th>
			<th>{{ trans('app.Due') }}</th>
			<th>{{ trans('app.Created') }}</th>
		</tr>
	</thead>
	<tbody>
	@foreach($list as $key=>$clt)
	<tr>
		<td>{{$key+1}}</td>
		<td>{{$clt->client->name}}</td>
		<td>{{$clt->total}}</td>
		<td>{{$clt->paid}}</td>
		<td>{{$clt->due}}</td>
		<td>{{date('Y-m-d', strtotime($clt->created_at))}}</td>
	</tr>
	@endforeach
	<tfoot>
		<tr class="danger">
			<td colspan="2">{{trans('app.Total')}}</td>
			<td>{{$list->sum('total')}}</td>
			<td>{{$list->sum('paid')}}</td>
			<td colspan="2">{{$list->sum('due')}}</td>
		</tr>
	</tfoot>
	</tbody> 
</table>