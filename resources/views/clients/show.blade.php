@extends('layouts.app')
@section('content')
<div class="container">
 
    <div class="row">
        <div class="col-md-12">
       		<div class="form-group pull-left">
			    <a class="btn btn-success print-window" href="#" role="button">{{ trans('app.Print') }}</a>
			</div>
			<table class="table table-bordered">
				<tbody>
				<tr class="warning">
					<td>{{ trans('app.ID') }}</td>
					<td>{{$client->id}}</td>
				</tr>
				<tr class="danger">
					<td>{{ trans('app.Client Name') }}</td>
					<td>{{$client->name}}</td>
				</tr>
				<tr class="active">
					<td>{{ trans('app.Total') }}</td>
					<td>{{$client->total}}</td>
				</tr>
				<tr class="active">
					<td>{{ trans('app.Paid') }}</td>
					<td>{{$client->paid}}</td>
				</tr>
				<tr class="active">
					<td>{{ trans('app.Due') }}</td>
					<td>{{$client->due}}</td>
				</tr>
				<tr class="info">
					<td>{{ trans('app.Created') }}</td>
					<td>{{$client->created_at}}</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	@if(count($client->installment))
    <div class="row">
        <div class="col-md-12">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<td colspan="5">المدفوعات</td>
					</tr>
					<tr class="active">
						<th>{{ trans('app.ID') }}</th>
						<th>{{ trans('app.Created') }}</th>
						<th>{{ trans('app.Total') }}</th>
						<th>{{ trans('app.Paid') }}</th>
						<th>{{ trans('app.Due') }}</th>
					</tr>
				</thead>
				<tbody>
				@foreach($client->installment as $key=>$clt)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{date('Y-m-d', strtotime($clt->created_at))}}</td>
					<td>{{$clt->total}}</td>
					<td>{{$clt->paid}}</td>
					<td>{{$clt->due}}</td>
				</tr>
				@endforeach
				</tbody>
				<tfoot>
					<tr class="info">
						<td colspan="3">المجموع</td>
						<td>{{$client->installment->sum('paid')}}</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	@endif
	<?php 
	 $list = \App\Orders::where('client_id',$client->id)->get();
	 ?>
	@if(!empty($list))
    <div class="row">
        <div class="col-md-12">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<td colspan="8">فواتير المبيعات</td>
					</tr>
					<tr>
						<th>{{trans('app.ID')}}</th> 
						<th>{{trans('app.Client Name')}}</th>
						<th>{{trans('app.Payment Type')}}</th>
						<th>{{trans('app.Total')}}</th>
						<th>{{trans('app.Paid')}}</th>
						<th>{{trans('app.Due')}}</th>
						<th>{{trans('app.Created')}}</th>
						<th>{{ trans('app.action') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($list as $item)
						<tr class="warning">
							<td> {{ $item->id }} </td>
							<td> {{ $item->client->name }} </td>
							<td> {{ $item->payment_type }} </td>
							<td> {{ $item->total }} </td>
							<td> {{ $item->paid }} </td>
							<td> {{ $item->due }} </td>
							<td> {{ $item->created_at }} </td>
							<td>
							<a class="btn btn-primary getdetiles" rel="details{{$item->id}}" href="#" >التفاصيل</a>
							<a target="_blank" class="btn btn-success" href="{{url('orders')}}/{{ $item->id
							}}">عرض الفاتورة</a>
							</td>
						</tr>
						<tr id="details{{$item->id}}" style="display: none;">
							<td colspan="8">
								<div class="row">
							        <div class="col-md-12">
							            <div class="panel panel-default">
							                <div class="panel-heading">
							                    <h3 class="panel-title text-center"><strong>تفاصيل الفاتورة رقم {{$item->id}}</strong>

							                    </h3>
												<a style="margin-top: -24px;" class="hidedetaile btn btn-sm btn-danger pull-left"  href="#" role="button">X</a>

							                </div>
							                <div class="panel-body">
							                    <div class="table-responsive">
							                        <table class="table table-bordered">
							                            <thead>
							                                <tr>
							                                    <td class="text-center">م</td>                    
                                    							<td><strong>إسم الصنف</strong></td>
							                                    <td class="text-center"><strong>الكمية</strong></td>
							                                    <td class="text-center"><strong>السعر</strong></td>
							                                    <td class="text-center"><strong>الإجمالى</strong></td>
							                                </tr>
							                            </thead>
							                            <tbody>
							                                <!-- foreach ($order->lineItems as $line) or some such thing here -->
							   
							                                @foreach($item->details as $key=>$prod)
							                                <tr>
							                                    <td class="text-center">{{$key+1}}</td>
                                    							<td>{{isset($prod->product->title)?$prod->product->title:'deleted'}}</td>
							                                    <td class="text-center">{{$prod->qty}}</td>
							                                    <td class="text-center">{{$prod->price}}</td>
							                                    <td class="text-center">{{$prod->total}}</td>
							                                </tr>
							                                @endforeach
							                                
							                            </tbody>
														<tfoot>
															<tr class="danger">
																<td colspan="4">المجموع</td>
																<td>{{$item->details->sum('total')}}</td>
															</tr>
														</tfoot>
							                        </table>
							                    </div>
							                </div>
							            </div>
							        </div>
							    </div>
							</td>
						</tr>
					@endforeach
				</tbody> 
			    <tfoot>
			        <tr class="info">
			        	<td colspan="3">المجموع</td>
			        	<td>{{$list->sum('total')}}</td>
			        	<td>{{$list->sum('paid')}}</td>
			        	<td>{{$list->sum('due')}}</td>
			        	<td></td>
			        	<td></td>
			        </tr>
			    </tfoot>
			</table>	
			 <div class="row text-center">
			 	{{-- $list->appends(\Request::except('page'))->render() --}}
			 </div>
		</div>
	</div>
	@endif
	@if(count($client->treasury))
    <div class="row">
        <div class="col-md-12">
        	<h5>{{ trans('app.treasuryMovement') }}</h5>
			<table class="table table-hover table-bordered">
				<thead>
					<tr class="active">
						<th>{{ trans('app.ID') }}</th>
						<th>{{ trans('app.Title') }}</th>
						<th>{{ trans('app.value') }}</th>
						<th>{{ trans('app.Created') }}</th>
					</tr>
				</thead>
				<tbody>
				@foreach($client->treasury as $key=>$clt)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{$clt->title}}</td>
					<td>{{$clt->value}}</td>
					<td>{{date('Y-m-d', strtotime($clt->created_at))}}</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	@endif
	<br/>
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<td colspan="6">المرتجعات</td>
					</tr>
					<tr class="active">
						<th>{{ trans('app.ID') }}</th>
						<th>تاريخ الإرجاع</th>
						<th>اسم الصنف</th>
						<th>{{ trans('app.Cost Price') }}</th>
						<th>{{ trans('app.Qantity') }}</th>
						<th>{{ trans('app.Total') }}</th>
					</tr>
				</thead>
				<tbody>
				<?php $cltId = $client->id; ?>
				<?php  $returns = \App\OrderReturnDetails::whereHas('invoice', function($query) use ($cltId) {
	        			$query->where('orders_returns.client_id',$cltId);
	    			})->get(); 
				?>
				@foreach($returns as $key=>$prod)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{$prod->invoice->created_at}}</td>
					<td>{{$prod->product->title}}</td>
					<td>{{$prod->cost}}</td>
					<td>{{$prod->qty}}</td>
					<td>{{$prod->total}}</td>
				</tr>
				@endforeach
				</tbody>
				<tfoot>
					<tr class="danger">
						<td colspan="4">{{trans('app.Total')}}</td>
						<td>{{ $returns->sum('qty') }}</td>
						<td>{{$returns->sum('total')}}</td>
					</tr>
				</tfoot>
			</table>
</div>
@stop()
@section('javascript')
<script type="text/javascript">
$(document).ready(function(){
	$(document).on("click",".getdetiles",function(e){
		e.preventDefault();
		var rel = $(this).attr("rel");
		$("#"+rel).slideDown();
		$('html, body').animate({
        	scrollTop: parseInt($("#"+rel).offset().top)
	    }, 2000);
	});
	$(document).on("click",".hidedetaile",function(e){
		e.preventDefault();
		$(this).closest("tr").fadeOut();
	});

});
</script>
@stop()