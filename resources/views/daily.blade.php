@extends('layouts.app')
@section('content')

<div class="container">
<div class="row">

            <div class="form-group pull-left">
                <a class="print-window" href="#" role="button">
                    <img title="{{ trans('app.Print') }}" alt="print.png" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAA7VBMVEUAAAD///+/v78fHx8kJCTOzs4iIiIeHh7Nzc0eHh4lJSUfHx/Ozs7KysrLy8vOzs7JyckfHx8hISHKysrKysrLy8sjIyPKysrLy8sgICAjIyMiIiLMzMwgICAiIiLLy8vLy8siIiLLy8vLy8vMzMwhISHMzMzLy8shISHKysohISHMzMwhISEgICAhISEhISEhISEiIiIhISEiIiIiIiIiIiIhISEgICAhISEgICAiIiIiIiLMzMwiIiLMzMzKysrKysohISHMzMzMzMwiIiLKysrKysogICDKysrMzMwiIiIiIiIiIiIiIiLMzMwE99FtAAAATXRSTlMAAgQQFRUWGRohIigqLC0vMDg9P0RUV1hZXV5gYGVoaW5wcnZ4eXp9goeJjpCWl5iZpKepqquwvb7FyMnZ5+jq6+zs7e7u9Pj6+/z9/mdK1+QAAADuSURBVDjL1dPZcoIwFIBhl7q0uNLWDdcq1rZUFPeoaKvWNXn/x2kSlCHHGYYbL/yvmMPHTCYJPp/XEn9CiSuQIkKpewSZFgStjAAUDQJN8Q6CkiSp10Cl4yAHU0wz4r9C8T6bTtj7yIk94u+aUIcPj2EKStglthDDDXQpGLqB4Y3BYbvtX8BPXgEV1lizNpID1IaftxGeI/RpAx0CHQlrQHIZJItgFMiC/OMLGPCzipKVabenxxni8x4F4TTtMUk+GnYzCmJPbP5gX4qXXbPh6Is8g0uZIwvT0Ya8AlBdFs+9W1eqAkDdBL15/ev/AfKTrIb81sQdAAAAAElFTkSuQmCC" />
                            </a>
                </a>
            </div>
	<div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">	
                <div style="font-weight: bold;" class="panel-heading">كشف حساب اليومية <h5 class="pull-left" style="margin-top: 3px;font-weight: bold;">تاريخ اليوم : {{$date}} </h5> </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">

                <?php 
                    $today = date('Y-m-d');
                    
                    $todayorder = DB::table('orders')->whereDate('created_at','>=',$today)->sum('paid');
                    
                    $prevorder = DB::table('orders')->whereDate('created_at','<',date('Y-m-d'))->sum('paid');
                    
                    $todaypurchase = DB::table('purchase_invoice')->whereDate('created_at','>=',$today)->sum('paid');
                    
                    $prevpurchase = DB::table('purchase_invoice')->whereDate('created_at','<',date('Y-m-d'))->sum('paid');
                    
                    $todaymasrof = DB::table('masrofat')->whereDate('created_at','>=',$today)->sum('value');
                    $prevmasrof = DB::table('masrofat')->whereDate('created_at','<',date('Y-m-d'))->sum('value');
                    $todaytreasury = DB::table('treasury_movement')->where('type',1)->whereDate('created_at','>=',$today)->sum('value');
                    $prevtreasury = DB::table('treasury_movement')->where('type',1)->whereDate('created_at','<',date('Y-m-d'))->sum('value');
                    $todaytreasurydeposite = DB::table('treasury_movement')->where('type',2)->whereDate('created_at','>=',$today)->sum('value');
                   
                    $prevtreasurydeposite = DB::table('treasury_movement')->where('type',2)->whereDate('created_at','<',date('Y-m-d'))->sum('value');

                    $clientinstallmentPaidtoday = DB::table('client_payments')->whereDate('created_at','>=',$today)->sum('paid');

                    $clientinstallmentPaidprev = DB::table('client_payments')->whereDate('created_at','<',date('Y-m-d'))->sum('paid');

                    $supllierpayment = DB::table('supplier_payments')->whereDate('created_at','>=',$today)->sum('paid');

                    $prevsupllierpayment = DB::table('supplier_payments')->whereDate('created_at','<',date('Y-m-d'))->sum('paid');
                    $first_balance = Config::get('custom-setting.current_balance');
                    $todayimport = 
                    $todayorder  + $todaytreasurydeposite + $clientinstallmentPaidtoday ; 
                    $todayexport = $todaypurchase + $todaymasrof + $todaytreasury + $supllierpayment;
                    $previmport = 
                    $prevorder  + $prevtreasurydeposite + $clientinstallmentPaidprev ; 
                    $prevexport = $prevpurchase + $prevmasrof + $prevtreasury + $prevsupllierpayment;
                    $prevbalance = $first_balance + $previmport - $prevexport ;
                    $todaybalance =  $todayimport - $todayexport ;
                ?>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <th>الحساب</th>
                                <th>صادر</th>
                                <th>وارد</th>
                            </thead>
                                <tbody>
                                <tr class="success">
                                    <td>{{ trans('app.todayorder') }}</td>
                                    <td></td>
                                    <td>{{ $todayorder }} </td>
                                </tr>
                                <tr class="success">
                                    <td>مدفوعات العملاء اليوم</td>
                                    <td></td>
                                    <td>{{ $clientinstallmentPaidtoday }} </td>
                                </tr>
                                <tr class="success">
                                    <td>حركة الخزينة اليوم إيداع</td>
                                    <td></td>
                                    <td>{{ $todaytreasurydeposite }} </td>
                                </tr>
                                <tr class="warning">
                                    <td>المجموع</td>
                                    <td></td>
                                    <td>{{ $todayimport }} </td>
                                </tr>

                                <tr class="danger">
                                    <td>{{ trans('app.todaypurchase') }}</td>
                                    <td>{{ $todaypurchase }} </td>
                                    <td></td>
                                </tr>
                                <tr class="danger">
                                    <td>{{ trans('app.todaymasrof') }}</td>
                                    <td>{{ $todaymasrof }} </td>
                                    <td></td>
                                </tr>
                                <tr class="danger">
                                    <td>{{ trans('app.todaytreasury') }} سحب</td>
                                    <td>{{ $todaytreasury }} </td>
                                    <td></td>
                                </tr>
                                <tr class="danger">
                                    <td>مدفوعات الموردين اليوم</td>
                                    <td>{{ $supllierpayment }} </td>
                                    <td></td>
                                </tr>
                                <tr class="warning">
                                    <td>المجموع</td>
                                    <td>{{ $todayexport }} </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>صافى</td>
                                    <td colspan="2">{{ $todaybalance }} </td>                          </tr>
                                <tr>
                                    <td>رصيد سابق</td>
                                    <td colspan="2">{{ $prevbalance }} </td>
                                </tr>
                                <tr>
                                    <td>اليومية</td>
                                    <td colspan="2">{{ $todaybalance + $prevbalance }} </td>
                                </tr>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@stop