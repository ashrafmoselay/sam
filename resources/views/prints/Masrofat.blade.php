@include('prints/print')
<table class="table table-bordered">
	
		<tr>
			<th>الرقم</th> 
			<th>{{trans('app.masrof Name')}}</th>
			<th>{{trans('app.value')}}</th>
			<th>{{trans('app.MasrofType')}}</th>
			<th>{{trans('app.sName')}}</th>
			<th>{{trans('app.Created')}}</th>
		</tr>
	
		@foreach($list as $item)
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->name }} </td>
				<td> {{ $item->value }} </td>
				<td> {{ $item->type }} </td>
				<td> {{ isset($item->sharek->name)?$item->sharek->name:trans('app.general') }} </td>
				<td> {{ $item->created_at }} </td>
			</tr>
		@endforeach
	
    
</table>