<table class="table table-hover table-bordered">
	<thead>
		<tr>
			<td colspan="11">العملاء</td>
		</tr>
		<tr>
			<th>@sortablelink('id',trans('app.ID'))</th>
			<th>@sortablelink('name',trans('app.Client Name'))</th>
			<th class="col-md-1">@sortablelink('qest_value',trans('app.qest_value'))</th>
			<th>@sortablelink('type','النوع')</th>
			<th class="col-md-1">@sortablelink('mobile',trans('app.Mobile'))</th>
			<th class="col-md-1">@sortablelink('total',trans('app.Total'))</th>
			<th class="col-md-1">@sortablelink('paid',trans('app.Paid'))</th>
			<th class="col-md-1">@sortablelink('due',trans('app.Due'))</th>
			<th class="col-md-1">تاريخ أخر قسط</th>
			<th class="col-md-1">عدد الاقساط المدفوعة</th>
			<th>{{ trans('app.action') }}</th>
		</tr>
	</thead>
	<tbody>
		@foreach($list as $item)
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->name }} </td>
				<td> {{ $item->qest_value }} </td>
				<td>  
				<?php 
					if($item->type==1){
						echo 'قطاعى';
					}elseif($item->type==2){
						echo "جملة";
					}else{
						echo "جملة الجملة";
					}
				?>
				</td>
				<td> {{ $item->mobile }} </td>
				<td> {{ $item->total }} </td>
				<td> {{ $item->paid }} </td>
				<td> {{ $item->due }} </td>
				<td> {{ !empty($item->installment->last())?date("Y-m-d", strtotime($item->installment->last()->created_at)):'' }} </td>
				<td>{{$item->installment->count()}}</td>
				<td>
				<a class="btn btn-primary" href="clients/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a> 
				@if($item->due>0)
				<a class="btn btn-success" href="clients/pay/{{ $item->id }}" role="button">{{ trans('app.pay') }}</a>
				@endif
				<a class="btn btn-default" href="clients/{{ $item->id }}" role="button">كشف حساب</a>
				<a class="btn btn-danger" href="clients/destroy/{{ $item->id }}" role="button">{{ trans('app.Delete') }}</a>

				</td>
			</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr class="danger">
			<td colspan="5">{{trans('app.Total')}}</td>
			<td>{{$list->sum('total')}}</td>
			<td>{{$list->sum('paid')}}</td>
			<td colspan="4">{{$list->sum('due')}}</td>
		</tr>
	</tfoot>
</table>
 <div class="row text-center">
 	{!! $list->appends(\Request::except('page'))->render() !!}
 </div>