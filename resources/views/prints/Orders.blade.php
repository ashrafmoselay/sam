@include('prints/print')
<h1>اﻷرباح</h1>
<table  class="table table-bordered">
	
		<tr>
			<th>{{trans('app.ID')}}</th>
			<th>{{trans('app.sName')}}</th>
			<th>{{trans('app.TotalMoney')}}</th>
			<th>{{trans('app.profit_percent')}}</th>
			<th>{{trans('app.Total Masrofat')}}</th>
			<th>{{trans('app.Total gMasrofat')}}</th>
			<th>{{ trans('app.Profit') }}</th>
			<th>{{ trans('app.Profit Value') }}</th>
		</tr>
	
		<?php 
			$shorka = \App\Shoraka::get();
	        $masrofat =  \App\Masrofat::where('type','1')->get();
	        $totalMasrofat = $masrofat->sum('value');
	        $totalprofit = DB::table('order_detailes')->sum(DB::raw('(qty * price) - (qty * cost)'));
	        $finalpofit = $totalprofit  - $totalMasrofat;
	        $finalpofit = round($finalpofit,2);
		?>
		@foreach($shorka as $item)
			<?php 
				$masrofatsptial =  \App\Masrofat::where('type','2')->where('sharek_id',$item->id)->sum('value');
				$profit = $finalpofit * ($item->profit_percent/100);
				//$profit -= $masrofat;
				$profit = round($profit,2);
				$msof = round($totalMasrofat/count($shorka),2);
			?>
			<tr>
				<td> {{ $item->id }} </td>
				<td> {{ $item->name }} </td>
				<td> {{ $item->total }} ج </td>
				<td> {{ $item->profit_percent }} % </td>
				<td> {{$masrofatsptial}} </td>
				<td> {{$msof}} </td>
				<td>{{$profit}} ج </td>
				<td>{{$profit - $masrofatsptial}} ج </td>
			</tr>
		@endforeach
	
		<tr>
			<td colspan="2">{{trans('app.Total')}} </td>
			<td colspan="4">{{$shorka->sum('total')}} ج</td>
			<td colspan="2">{{$finalpofit}} ج </td>
		</tr>
</table>
<br/><br/>
<h1>المبيعات</h1>
<table style="font-size:12px!important;" class="table table-bordered">
	<tr>
		<th>الرقم</th> 
		<th>{{trans('app.Client Name')}}</th>
		<th>{{trans('app.Payment Type')}}</th>
		<th>{{trans('app.Total')}}</th>
		<th>{{trans('app.Paid')}}</th>
		<th>{{trans('app.Due')}}</th>
		<th>{{trans('app.Created')}}</th>
	</tr>
	@foreach($list as $item)
		<tr>
			<td> {{ $item->id }} </td>
			<td> {{ $item->client->name }} </td>
			<td> {{ $item->payment_type }} </td>
			<td> {{ $item->total }} </td>
			<td> {{ $item->paid }} </td>
			<td> {{ $item->due }} </td>
			<td> {{ $item->created_at }} </td>
		</tr>
	@endforeach
    <tr class="info">
    	<td colspan="3">المجموع</td>
    	<td>{{$list->sum('total')}}</td>
    	<td>{{$list->sum('paid')}}</td>
    	<td>{{$list->sum('due')}}</td>
    	<td></td>
    </tr>
    
</table>