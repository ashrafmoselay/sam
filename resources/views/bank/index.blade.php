@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
       		<div class="form-group pull-left">
			    <a class="btn btn-success" href="{{url('bank/create')}}" role="button">اضافة بنك</a>
			</div>
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			<table class="table table-hover display table-bordered">
				<thead>
					<tr>
						<th>م</th>
						<th>اسم البنك</th>
						<th>رقم الحساب</th>
						<th>رصيد البنك</th>
						<th>{{ trans('app.action') }}</th>
					</tr>
				</thead> 
				<tbody>
					@foreach($list as $key=>$item)
						<tr>
							<td> {{ $item->id }} </td>
							<td> {{ $item->name }} </td>
							<td> {{ $item->number }} </td>
							<td> {{ $item->balance }} </td>
							<td>
							<a class="btn btn-primary" href="bank/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a>
							<a class="btn btn-warning" href="bank/{{ $item->id }}" role="button">عرض العمليات</a>
							@if($item->balance>0)
							<a class="btn btn-danger" href="bank/withdraw/{{ $item->id }}"
							   role="button">سحب او ايداع</a>
							@endif

							</td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr class="danger">
						<td colspan="3">المجموع</td>
						<td>{{round($list->sum('balance'),2)}}</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
@stop()
