@include('prints/print')
<table class="table table-bordered">
	
		<tr>
			<th>الرقم</th> 
			<th>{{trans('app.user_type')}}</th>
			<th>{{trans('app.Opertion Type')}}</th>
			<th>{{trans('app.Title')}}</th>
			<th>البيان</th>
			<th>{{trans('app.value')}}</th>
			<th>{{trans('app.Created')}}</th>
		</tr>
	
		@foreach($list as $item)
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->user_type }} </td>
				<td> {{ $item->type }} </td>
				<td> {{ $item->name }} </td>
				<td> {{ $item->title }} </td>
				<td> {{ $item->value }} </td>
				<td> {{ $item->created_at }} </td>
			</tr>
		@endforeach
	
	</table>