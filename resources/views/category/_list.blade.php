
<table class="table table-hover table-bordered">
	<thead>
		<tr>
			<th>@sortablelink('id',trans('app.ID'))</th> 
			<th>@sortablelink('name',trans('app.Category Name'))</th>
			<th>@sortablelink('type','النوع')</th>
			<th>عدد المنتجات</th>
			<th>@sortablelink('created_at',trans('app.action'))</th>
		</tr>
	</thead>
	<tbody>
		@foreach($list as $item)
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->name }} </td>
				<td> {{ ($item->type==1)?'فئة رئيسية':'فئة فرعية' }} </td>
				<td><a href="">{{($item->products->count())?$item->products->count():$item->products2->count()}}</a></td>
				<td>

				<a class="btn btn-primary" href="category/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a> 
				<a class="btn btn-danger" href="category/destroy/{{ $item->id }}" role="button">{{ trans('app.Delete') }}

				</td>
			</tr>
		@endforeach
	</tbody>
</table>
 <div class="row text-center">
 	{!! $list->appends(\Request::except('page'))->render() !!}
 </div>