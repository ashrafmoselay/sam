@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">{{ trans('app.Summary Report') }} <h5 class="pull-left" style="margin-top: -8px;">تاريخ اليوم : {{$date}}  <span class="btn btn-primary" style=" color: #ffffff;height: 35px; max-height: 35px;min-width: 89px;font-weight: bold; margin-right: 10px;font-size: 15px; " id="clock"></span></h5> </div>
        <?php 
            $totalProduct = DB::table('products')->count();
            $todayorder = DB::table('orders')->where('created_at','>=',date('Y-m-d'))->sum('paid');
            $todaypurchase = DB::table('purchase_invoice')->where('created_at','>=',date('Y-m-d'))->sum('paid');
            //dd($todayorder);
            $totalorders = DB::table('orders')->count();
            $totalSales = DB::table('orders')->sum('total') - DB::table('orders')->sum('discount');
            $purchase_Count = DB::table('purchase_invoice')->count();
            $totalpurchase = DB::table('purchase_invoice')->sum('total');
            $totalDebt = DB::table('clients')->sum('due');
            $supplierDebt = DB::table('suppliers')->sum('due');
            $supplierCountdue = DB::table('suppliers')->where('due','>',0)->count();
            $totalprofit = DB::table('order_detailes')->sum(DB::raw('(qty * price) - (qty * cost)'));

            $query = DB::table('products')
            ->join('products_store','product_id','products.id')
            //->where('store_id',$item->id)
            ->select(DB::raw('sum((qty-sale_count)*products_store.cost)  as TotalCost'))->first();
            $totalRemainInStock = isset($query->TotalCost)?$query->TotalCost:0;
            $reminqty = DB::table('products_store')->sum(DB::raw('(qty - sale_count)'));
            $clientCountdue = DB::table('clients')->where('due','>',0)->count();
            $clientCount = DB::table('clients')->count();
            $supplierCount = DB::table('suppliers')->count();
            $totalmasrof = DB::table('masrofat')->where('type',1)->sum('value');
            $stotalmasrof = DB::table('masrofat')->where('type',2)->sum('value');
            $todaymasrof = DB::table('masrofat')->where('created_at','>=',date('Y-m-d'))->sum('value');
            $todaytreasury = DB::table('treasury_movement')->where('type',1)->where('created_at','>=',date('Y-m-d'))->sum('value');
            $todaytreasurydeposite = DB::table('treasury_movement')->where('type',2)->where('created_at','>=',date('Y-m-d'))->sum('value');
            $finalprofit = "إجمالى اﻷرباح - إجمالى المصروفات <br>";
            $finalprofit .= round($totalprofit,2) ." - ". $totalmasrof;
            $final = round($totalprofit,2)-$totalmasrof;
            $finalprofit .= " = ".$final;
            $totalpurchasepaid = DB::table('purchase_invoice')->sum('paid');
            $allmasrof = DB::table('masrofat')->sum('value');
            $ordersPaid = DB::table('orders')->sum('paid');
            $clientinstallmentPaid = DB::table('client_payments')->sum('paid');
            $clientinstallmentPaidtoday = DB::table('client_payments')->where('created_at','>=',date('Y-m-d'))->sum('paid');
            $clientsolfa = DB::table('treasury_movement')->where('type',1)->sum('value');
            $deposite = DB::table('treasury_movement')->where([['type',2]/*,['user_type','<>','3']*/])->sum('value');
            $supllierpayment = DB::table('supplier_payments')->sum('paid');
            $first_balance = Config::get('custom-setting.current_balance');
            $balance = ($ordersPaid + $first_balance +$clientinstallmentPaid + $deposite) - $totalpurchasepaid - $allmasrof - $clientsolfa - $supllierpayment;
            $b= "الرصيد = ( رصيد أول المدة + المبيعات + مدفوعات العملاء + حركة الخزينة إيداع ) - ( المشتريات - المصروفات - حركة الخزينة سحب - مدفوعات الموردين ) "."<br>الرصيد = ";

            $b .= $first_balance ." + ". $ordersPaid ." + ". $clientinstallmentPaid ." + ".$deposite ." - ". $totalpurchasepaid ." - ". $allmasrof ." - ". $clientsolfa ." - ". $supllierpayment; 
        ?>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover table-bordered">
                        <tbody>
                        <tr class="warning">
                            <td>{{ trans('app.todayorder') }}</td>
                            <td>{{ $todayorder }} {{ trans('app.EGP') }}</td>
                        </tr>
                        <tr class="warning">
                            <td>مدفوعات العملاء اليوم</td>
                            <td>{{ $clientinstallmentPaidtoday }} {{ trans('app.EGP') }}</td>
                        </tr>
                        <tr class="warning">
                            <td>{{ trans('app.todaypurchase') }}</td>
                            <td>{{ $todaypurchase }} {{ trans('app.EGP') }}</td>
                        </tr>
                        <tr class="warning">
                            <td>{{ trans('app.todaymasrof') }}</td>
                            <td>{{ $todaymasrof }} {{ trans('app.EGP') }}</td>
                        </tr>
                        <tr class="warning">
                            <td>{{ trans('app.todaytreasury') }} سحب</td>
                            <td>{{ $todaytreasury }} {{ trans('app.EGP') }}</td>
                        </tr>
                        <tr class="warning">
                            <td>حركة الخزينة اليوم إيداع</td>
                            <td>{{ $todaytreasurydeposite }} {{ trans('app.EGP') }}</td>
                        </tr>
                        <tr class="info">
                            <td>{{ trans('app.today') }}</td>
                            <td>{{ $todayorder + $clientinstallmentPaidtoday + $todaytreasurydeposite - $todaypurchase - $todaymasrof - $todaytreasury }} {{ trans('app.EGP') }}</td>
                        </tr>
                        <tr data-html="true" data-toggle="tooltip" title="{!! $b !!}" class="success">
                            <td>{{ trans('app.balance') }} ( النقدية )</td>
                            <td>{{ $balance }} {{ trans('app.EGP') }}</td>
                        </tr>
                        <tr class="success">
                            <td>{{ trans('app.Total debt') }} </td>
                            <td>{{$totalDebt}} {{ trans('app.EGP') }} {{"( $clientCountdue )"}} {{trans('app.Client')}} </td>
                        </tr>
                        <tr class="success">
                            <td>الجرد</td>
                             <td>{{round($totalRemainInStock,2)}} {{ trans('app.EGP') }} ( {{round($reminqty,2)}} ) الكمية المتاحة من المنتجات </td>
                        </tr>
                        <tr class="danger">
                            <td>مديونات الموردين</td>
                            <td>{{$supplierDebt}} {{ trans('app.EGP') }} {{"( $supplierCountdue )"}} مورد </td>
                        </tr>
                        <tr class="warning">
                            <td>إجمالى رأس المال</td>
                             <td>{{$balance+$totalDebt+round($totalRemainInStock,2)-$supplierDebt}} {{ trans('app.EGP') }}</td>
                        </tr>
                        <tr class="info">
                            <td>{{ trans('app.Total Products') }}</td>
                            <td>{{" $totalProduct "}} {{ trans('app.product') }} ( {{round($reminqty,2)}} ) الكمية المتاحة من المنتجات</td>
                        </tr>
                        <tr class="warning">
                            <td>{{ trans('app.Today Sales') }}</td>
                            <td>{{$totalSales. " ".trans('app.EGP')." ( $totalorders )"}} {{ trans('app.order') }}</td>
                        </tr>
                        <tr class="info">
                            <td>{{ trans('app.Total Purchase') }}</td>
                             <td>{{$totalpurchase." ".trans('app.EGP')." ( $purchase_Count ) "}}{{ trans('app.invoice') }}</td>
                        </tr>
                        <tr class="success">
                            <td>{{ trans('app.Total Profit') }}</td>
                            <td>{{round($totalprofit,2). " ".trans('app.EGP')}}</td>
                        </tr>
                        <tr class="info">
                            <td>{{ trans('app.Total gMasrofat') }}</td>
                            <td>{{$totalmasrof. " ".trans('app.EGP')}}</td>
                        </tr>
                        <tr class="info">
                            <td>{{ trans('app.Total Masrofat') }}</td>
                            <td>{{$stotalmasrof. " ".trans('app.EGP')}}</td>
                        </tr>

                        <tr data-html="true" data-toggle="tooltip" title="{{$finalprofit}}" class="{{($final<0)?"danger":"warning"}}">
                            <td>{{ trans('app.final_profit') }}</td>
                             <td>{{round($final,2)}} {{ trans('app.EGP') }} </td>
                        </tr>
                        <tr class="success">
                            <td>{{ trans('app.Total Cost Remaining In Stock') }}</td>
                             <td>{{round($totalRemainInStock,2)}} {{ trans('app.EGP') }} ( {{round($reminqty,2)}} ) الكمية المتاحة من المنتجات </td>
                        </tr>
                        <tr class="active">
                            <td>{{ trans('app.Client Count') }}</td>
                             <td>{{$clientCount}} </td>
                        </tr>
                        <tr class="warning">
                            <td>{{ trans('app.Supplier Count') }}</td>
                             <td>{{$supplierCount}} </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <p> جميع الحقوق محفوظة 2017 &copy;<p>
            <p class="pull-left"><a style="margin-top: -33px" href="#" class="btn btn-success btn-block" data-toggle="modal" data-target="#myModalDeveloper">معلومات عن المبرمج</a></p>
        </div>
    </div>
</div>
</div>
</div>
@stop

@section('javascript')
<script type="text/javascript">
    function updateClock ( )
        {
        var currentTime = new Date ( );
        var currentHours = currentTime.getHours ( );
        var currentMinutes = currentTime.getMinutes ( );
        var currentSeconds = currentTime.getSeconds ( );

        // Pad the minutes and seconds with leading zeros, if required
        currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
        currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

        // Choose either "AM" or "PM" as appropriate
        var timeOfDay = ( currentHours < 12 ) ? "ص" : "م";

        // Convert the hours component to 12-hour format if needed
        currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

        // Convert an hours component of "0" to "12"
        currentHours = ( currentHours == 0 ) ? 12 : currentHours;

        // Compose the string for display
        var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
        
        
        $("#clock").html(currentTimeString);
            
     }

    $(document).ready(function()
    {
        $('[data-toggle="tooltip"]').tooltip(); 
       setInterval('updateClock()', 1000);
    });
</script>
@stop