<div class="row {{(Config::get('custom-setting.show_profit')==2)?'hide':''}}">
	<div class="col-md-12">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<td colspan="8">اﻷرباح</td>
				</tr>
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
			</thead> 
			<tbody>
				<?php 
					$shorka = \App\Shoraka::get();
				?>
				@foreach($shorka as $item)
					<?php 
					$from = Request::get('from');
					$to = Request::get('to');
					//dd($to);
					$query =  \App\Masrofat::where('type','2')->where('sharek_id',$item->id);
					if(!empty($from) && !empty($to)){
				       $query = $query->whereBetween('created_at', array($from, $to));
				    }else if(!empty($from)){
				       $query = $query->where('created_at','>=',$from);
				    }else if(!empty($to)){
				       $query = $query->where('created_at','<=',$to);
				    }
				    $masrofat = $query->sum('value');
				    //totalprofit
					$profit = $totalprofit * ($item->profit_percent/100);
						//$profit -= $masrofat;
					$profit = round($profit,2);

					$msof = round($totalMasrofat/count($shorka),2);
					
					?>
					<tr>
						<td> {{ $item->id }} </td>
						<td> {{ $item->name }} </td>
						<td> {{ $item->total }} {{trans('app.EGP')}} </td>
						<td> {{ $item->profit_percent }} % </td>
						<td> {{$masrofat}} </td>
						<td> {{$msof}} </td>
						<td>{{$profit}} {{trans('app.EGP')}} </td>
						<td>{{$profit - $masrofat - $msof}} {{trans('app.EGP')}} </td>
					</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2">{{trans('app.Total')}} </td>
					<td colspan="4">{{$shorka->sum('total')}} {{trans('app.EGP')}}</td>
					<td colspan="2">{{round($finalpofit,2)}} {{trans('app.EGP')}} </td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<table class="table table-hover table-bordered">
	<thead>
		<tr>
			<td colspan="10">المبيعات</td>
		</tr>
		<tr>
			<th></th>
			<th>@sortablelink('id',trans('app.ID'))</th> 
			<th>@sortablelink('client_id',trans('app.Client Name'))</th>
			<th>@sortablelink('payment_type',trans('app.Payment Type'))</th>
			<th>@sortablelink('total',trans('app.Total'))</th>
			<th>@sortablelink('discount','قيمة الخصم')</th>
			<th>@sortablelink('paid',trans('app.Paid'))</th>
			<th>@sortablelink('due',trans('app.Due'))</th>
			<th>@sortablelink('created_at',trans('app.Created'))</th>
			<th>{{ trans('app.action') }}</th>
		</tr>
	</thead>
	<tbody>
		@php 
		$totaldiscount = 0;
		@endphp
		@foreach($list as $i=>$item)
			<tr>

				<td> {{ $i+1 }} </td>
				<td> {{ $item->id }} </td>
				<td> {{ $item->client->name ." ( ". $item->client_id." )" }} </td>
				<td> {{ $item->payment_type }} </td>
				<td> {{ $item->total }} </td>
				<td> 
				@php
					$discount=$item->discount;
                    $dist = $item->discount.' ج.م';
                    if($item->discount){
                        if($item->discount_type==2){
                            $discount=$item->total*($item->discount/100);
                            $dist = "%".$item->discount. " ( $discount ج.م )";
                        }
                    }
                    $totaldiscount += $discount;
                @endphp
                {{$dist}}
				</td>
				<td> {{ $item->paid }} </td>
				<td> {{ $item->due }} </td>
				<td> {{ $item->created_at }} </td>
				<td>
				<a class="btn btn-success" href="orders/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a>
				<a class="btn btn-primary" href="orders/{{ $item->id }}" role="button">{{ trans('app.Show') }}</a>
				</td>
			</tr>
		@endforeach
	</tbody>
    <tfoot>
        <tr class="info">
        	<td colspan="4">المجموع</td>
        	<td>{{$list->sum('total')}}</td>
        	<td>{{$totaldiscount}}</td>
        	<td>{{$list->sum('paid')}}</td>
        	<td>{{$list->sum('due')}}</td>
        	<td></td>
        	<td></td>
        </tr>
    </tfoot>
</table>
 <div class="row text-center">
 	{!! $list->appends(\Request::except('page'))->render() !!}
 </div>
