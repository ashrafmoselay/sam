
<table class="table table-hover table-bordered">
	<thead>
		<tr>
			<th>@sortablelink('id',trans('app.ID'))</th> 
			<th>@sortablelink('name',trans('app.notice'))</th>
			<th>@sortablelink('name','صاحب العملية')</th>
			<th>@sortablelink('user_type',trans('app.user_type'))</th>
			<th>@sortablelink('type',trans('app.Opertion Type'))</th>
			<th>@sortablelink('value',trans('app.value'))</th>
			<th>@sortablelink('created_at',trans('app.Created'))</th>
		</tr>
	</thead>
	<tbody>
		@foreach($list as $item)
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->title }} </td>
				<td> {{ $item->name }} </td>
				<td> {{ $item->user_type }} </td>
				<td> {{ $item->type }} </td>
				<td> {{ $item->value }} </td>
				<td> {{ $item->created_at }} </td>
			</tr>
		@endforeach
	</tbody>
</table>
 <div class="row text-center">
 	{!! $list->appends(\Request::except('page'))->render() !!}
 </div>