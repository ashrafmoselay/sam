
<table class="table table-hover table-bordered">
	<thead>
		<tr>
			<th>@sortablelink('id',trans('app.ID'))</th> 
			<th>@sortablelink('name',trans('app.masrof Name'))</th>
			<th>@sortablelink('value',trans('app.value'))</th>
			<th>@sortablelink('type',trans('app.MasrofType'))</th>
			<th>@sortablelink('sharek_id',trans('app.sName'))</th>
			<th>@sortablelink('created_at',trans('app.Created'))</th>
			<th>@sortablelink('created_at',trans('app.action'))</th>
		</tr>
	</thead>
	<tbody>
		@foreach($list as $item)
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->name }} </td>
				<td> {{ $item->value }} </td>
				<td> {{ $item->type }} </td>
				<td> {{ isset($item->sharek->name)?$item->sharek->name:trans('app.general') }} </td>
				<td> {{ $item->created_at }} </td>
				<td>

				<a class="btn btn-primary" href="masrofat/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a> 
				<a class="btn btn-danger" href="masrofat/destroy/{{ $item->id }}" role="button">{{ trans('app.Delete') }}

				</td>
			</tr>
		@endforeach
	</tbody>
    <tfoot>
        <tr class="info">
        	<td colspan="2">المجموع</td>
        	<td colspan="5">{{$list->sum('value')}}</td>
        </tr>
    </tfoot>
</table>
 <div class="row text-center">
 	{!! $list->appends(\Request::except('page'))->render() !!}
 </div>