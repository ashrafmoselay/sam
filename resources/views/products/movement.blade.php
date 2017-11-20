@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
       		<div class="form-group pull-left">
			    <a class="btn btn-success print-window" href="#" role="button">{{ trans('app.Print') }}</a>
			</div>
       		<div class="form-group pull-right">
			   <h4>متابعة حركة صنف ( {{$product->title}} )</h4>
			</div>
			<?php 
				$store =\App\ProductsStore::where('product_id',$product->id)->groupBy('store_id')->selectRaw('*, sum(qty-sale_count) as sum')->get();
				$storeqty = \App\ProductsStore::where('product_id',$product->id)->sum('qty-sale_count');
			?>
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<td colspan="3">كمية المنتج بالمخازن</td>
					</tr>
					<tr class="active">
						<td>م</td>
						<td>المخزن</td>
						<td>الكمية</td>
					</tr>
				</thead>
				<tbody>
				@foreach($store as $key=>$s)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{($s->store)?$s->store->address:''}}</td>
					<td><span style="direction: ltr !important;float: right;margin-left: 5px;">{{$product->decToFraction($s->sum)}}</span> {{($s->unit_id)?$s->unit->title:''}}</td>
				</tr>
				@endforeach
				</tbody>
			</table>
			<br/>

			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<td colspan="7">فواتير المشتريات</td>
					</tr>
					<tr class="active">
						<th>{{ trans('app.ID') }}</th>
						<th>{{ trans('app.Invoice ID') }}</th>
						<th>{{ trans('app.Created') }}</th>
						<th>{{ trans('app.Products') }}</th>
						<th>{{ trans('app.Cost Price') }}</th>
						<th>{{ trans('app.Qantity') }}</th>
						<th>{{ trans('app.Total') }}</th>
					</tr>
				</thead>
				<tbody>
				@foreach($purchaseList as $key=>$prod)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{$prod->invoice_id}} ( {{$prod->invoice->supplier->name}} )</td>
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
						<td colspan="5">{{trans('app.Total')}}</td>
						<?php 
							$invoiceqty = $purchaseList->sum('qty');
							$totalqty = $totalqty +$storeqty;
							$diff =  $totalqty - $invoiceqty;
						?>
						<td>{{ ($diff>0)?$invoiceqty . " +  $diff ( ".trans('app.intial')." ) = $totalqty":$invoiceqty }}</td>
						<td>{{$purchaseList->sum('total')}}</td>
					</tr>
				</tfoot>
			</table>
			<br/>
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<td colspan="10">فواتير المبيعات</td>
					</tr>
					<tr class="active">
						<th>{{ trans('app.ID') }}</th>
						<th>{{ trans('app.Invoice ID') }}</th>
						<th>{{ trans('app.Created') }}</th>
						<th>{{ trans('app.Products') }}</th>
						<th>المخزن</th>
						<th>{{ trans('app.Cost Price') }}</th>
						<th>{{ trans('app.Sale Price') }}</th>
						<th>{{ trans('app.Qantity') }}</th>
						<th>الوحدة</th>
						<th>{{ trans('app.Total') }}</th>
					</tr>
				</thead>
				<tbody>
				@foreach($salesList as $key=>$prod)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{$prod->order_id}} ( {{$prod->invoice->client->name }} )</td>
					<td>{{$prod->invoice->created_at}}</td>
					<td>{{isset($prod->product->title)?$prod->product->title:''}}</td>
					<td>{{$prod->store->address}}</td>
					<td>{{$prod->cost}}</td>
					<td>{{$prod->price}}</td>
					<td>{{$prod->qty}}</td>
					<td>{{isset($prod->unit->title)?$prod->unit->title:'عدد'}}</td>
					<td>{{$prod->total}}</td>
				</tr>
				@endforeach
				</tbody>
				<tfoot>
					<tr class="danger">
						<td colspan="7">{{trans('app.Total')}}</td>
						<td>{{ $salesList->sum('qty') }}</td>
						<td></td>
						<td>{{$salesList->sum('total')}}</td>
					</tr>
				</tfoot>
			</table>
			<br/>
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<td colspan="7">مرتجعات المشتريات</td>
					</tr>
					<tr class="active">
						<th>{{ trans('app.ID') }}</th>
						<th>المورد</th>
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
					<td>{{$prod->invoice->supplier->name}}</td>
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
						<td colspan="5">{{trans('app.Total')}}</td>
						<td>{{ $returns->sum('qty') }}</td>
						<td>{{$returns->sum('total')}}</td>
					</tr>
				</tfoot>
			</table>
			<br/>
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<td colspan="7">مرتجعات المبيعات</td>
					</tr>
					<tr class="active">
						<th>{{ trans('app.ID') }}</th>
						<th>العميل</th>
						<th>تاريخ الإرجاع</th>
						<th>اسم الصنف</th>
						<th>السعر</th>
						<th>{{ trans('app.Qantity') }}</th>
						<th>{{ trans('app.Total') }}</th>
					</tr>
				</thead>
				<tbody>
				<?php $list = \App\OrderReturnDetails::where('product_id',$product->id)->get(); ?>
				@foreach($list as $key=>$prod)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{$prod->invoice->client->name}}</td>
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
						<td colspan="5">{{trans('app.Total')}}</td>
						<td>{{ $list->sum('qty') }}</td>
						<td>{{$list->sum('total')}}</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
@stop()