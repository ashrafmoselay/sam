
<table class="table table-hover table-bordered">
	<thead>
		<tr>
			<td>م</td>
			<td>الدور</td>
			<td>اسم الظهور</td>
			<td>العملية</td>
		</tr>
	</thead>
	<tbody>
		@foreach($list as $item)
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->name }} </td>
				<td>{{ $item->display_name }}</td>
				<td>
				<a class="btn btn-primary" href="role/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a> 
				<a class="btn btn-danger" href="role/destroy/{{ $item->id }}" role="button">{{ trans('app.Delete') }}

				</td>
			</tr>
		@endforeach
	</tbody>
</table>