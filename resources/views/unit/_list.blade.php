
<table class="table table-hover table-bordered">
	<thead>
		<tr>
			<th>الوحدة</th>
			<th>العملية</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($list as $item)
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->title }} </td>
				<td>

				<a class="btn btn-primary" href="unit/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a> 
				<a class="btn btn-danger" href="unit/destroy/{{ $item->id }}" role="button">{{ trans('app.Delete') }}

				</td>
			</tr>
		@endforeach
	</tbody>
</table>
 <div class="row text-center">
 	{!! $list->appends(\Request::except('page'))->render() !!}
 </div>