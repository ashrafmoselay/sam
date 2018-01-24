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
					<td>{{$suppliers->id}}</td>
				</tr>
				<tr class="danger">
					<td>{{ trans('app.Supplier Name') }}</td>
					<td>{{$suppliers->name}}</td>
				</tr>
				<tr class="active">
					<td>{{ trans('app.Total') }}</td>
					<td>{{$suppliers->total}}</td>
				</tr>
				<tr class="active">
					<td>{{ trans('app.Paid') }}</td>
					<td>{{$suppliers->paid}}</td>
				</tr>
				<tr class="active">
					<td>{{ trans('app.Due') }}</td>
					<td>{{$suppliers->due}}</td>
				</tr>
				<tr class="info">
					<td>{{ trans('app.Created') }}</td>
					<td>{{$suppliers->created_at}}</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
    <div class="row">
        <div class="col-md-12">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<td colspan="7">المدفوعات</td>
					</tr>
					<tr class="active">
						<th>{{ trans('app.ID') }}</th>
						<th>{{ trans('app.esal_num') }}</th>
						<th>{{ trans('app.Created') }}</th>
						<th>{{ trans('app.Total') }}</th>
						<th>طريقة الدفع</th>
						<th>{{ trans('app.Paid') }}</th>
						<th>{{ trans('app.Due') }}</th>
					</tr>
				</thead>
				<tbody>
				@foreach($suppliers->installment as $key=>$clt)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{$clt->esal_num}}</td>
					<td>{{date('Y-m-d', strtotime($clt->created_at))}}</td>
					<td>{{$clt->total}}</td>
					<td>{{$clt->payment_type}}</td>
					<td>{{$clt->paid}}</td>
					<td>{{$clt->due}}</td>
				</tr>
				@endforeach
				</tbody>
				<tfoot>
				<tr class="info">
					<td colspan="3">المجموع</td>
					<td>{{$suppliers->installment->sum('paid')}}</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				</tfoot>
			</table>
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<td colspan="7">فواتير المشتريات</td>
					</tr>
					<tr>
						<th>{{trans('app.ID')}}</th>
						<th>{{trans('app.Supplier Name')}}</th>
						<th>{{trans('app.Total')}}</th>
						<th>{{trans('app.Paid')}}</th>
						<th>{{trans('app.Due')}}</th>
						<th>{{trans('app.Created')}}</th>
						<th>{{ trans('app.action') }}</th>
					</tr>
				</thead>
				<tbody>
					<?php  $list = \App\PurchaseInvoice::where('supplier_id',$suppliers->id)->get(); ?>
					@foreach($list as $item)
						<tr class="warning">
							<td> {{ $item->id }} </td>
							<td> {{ $item->supplier->name }} </td>
							<td> {{ $item->total }} </td>
							<td> {{ $item->paid }} </td>
							<td> {{ $item->due }} </td> 
							<td> {{ $item->created_at }} </td>
							<td>
							<a class="btn btn-primary getdetiles" rel="details{{$item->id}}" href="#" >التفاصيل</a>
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
							                                    <td class="text-center">{{$prod->cost}}</td>
							                                    <td class="text-center">{{$prod->total}}</td>
							                                </tr>
							                                @endforeach
							                                
							                            </tbody>
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
			        <tr class="danger">
			        	<td colspan="2">المجموع</td>
			        	<td>{{$list->sum('total')}}</td>
			        	<td>{{$list->sum('paid')}}</td>
			        	<td colspan="3">{{$list->sum('due')}}</td>
			        </tr>
			    </tfoot>
			</table>
			<?php $supId = $suppliers->id; ?>
			<?php  $returns = \App\ReturnDetails::whereHas('invoice', function($query) use ($supId) {
        			$query->where('returns.supplier_id',$supId);
    			})->get(); 
			?>
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
	</div>
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