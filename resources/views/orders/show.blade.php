@extends('layouts.app')
@section('content')
<style type="text/css">
    .invoice-title h2, .invoice-title h3 {
    display: inline-block;
}
.table {
    border: 2px solid;
}
.table td {
    border: 2px solid black;
}
#footer{
    width: 100%;
    visibility: hidden;
     position: fixed;
     bottom: 10px;
     text-align: center;
}
@media print {
    #footer{visibility: visible;}
     a{
     visibility:hidden;
     }

}

</style>
<div class="container">

    <div class="row">
        <div class="col-xs-12">
            <div class="pull-left">
                <a class="btn print-window" href="#" role="button">
                <h1>
               <i class="fa fa-print" aria-hidden="true"></i>
               </h1>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="invoice-title">
                <h2>
                    {{Config::get('custom-setting.SiteName')}}
                </h2>
                    
                <h3 class="pull-left">رقم الفاتورة : {{$invoice->id}}</h3>
                @if(Config::get('custom-setting.mobile'))
                <br><span>{{Config::get('custom-setting.mobile')}}</span>
                @endif
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <address>
                    كود العميل : {{$invoice->client->id}} <br>
                    اسم العميل : {{$invoice->client->name}}
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                    نوع الفاتورة : {{$invoice->payment_type}} <br>
                    التاريخ : {{$invoice->created_at}}
                    </address>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>اﻷصناف</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td class="text-center">م</td>
                                    <td class="text-center"><strong>إسم الصنف</strong></td>
                                    <td class="text-center"><strong>الكمية</strong></td>
                                    <td class="text-center">الوحدة</td>
                                    <td class="text-center"><strong>السعر</strong></td>
                                    <td class="text-center"><strong>الإجمالى</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                @foreach($invoice->details as $key=>$prod)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">{{isset($prod->product->title)?$prod->product->title:'deleted'}}</td>
                                    <td class="text-center">{{$prod->qty}}</td>
                                    <td class="text-center">{{isset($prod->unit->title)?$prod->unit->title:'عدد'}}</td>
                                    <td class="text-center">{{$prod->price}}</td>
                                    <td class="text-center">{{$prod->total}}</td>
                                </tr>
                                @endforeach
                                
                            </tbody>
                        </table>
                        <table class="table-condensed pull-left">
                            <tbody>
                                <tr>
                                    <td class="no-line text-center"><strong>إجمالى الفاتورة : </strong></td>
                                    <td class="no-line text-right">{{$invoice->total}}</td>
                                    <td class="no-line text-center"><strong>قيمة الخصم : </strong></td>
                                    <td class="no-line text-right">
                                        
                                        @php
                                            $discount = 0;
                                            $dist = $invoice->discount.' ج.م';
                                            if($invoice->discount){
                                                if($invoice->discount_type==2){
                                                    $discount=$invoice->total*($invoice->discount/100);
                                                    $dist = "%".$invoice->discount. " ( $discount ج.م )";
                                                }
                                            }
                                        @endphp
                                        {{$dist}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="no-line text-center"><strong>المبلغ المدفوع : </strong></td>
                                    <td class="no-line text-right">{{$invoice->paid}}</td>
                                <td class="no-line text-center"><strong>المبلغ المتبقى : </strong></td>
                                <td class="no-line text-right">{{$invoice->due}}</td>
                                </tr>
                                <tr>
                                <td class="no-line text-center"><strong>رصيد سابق : </strong></td>
                                <td class="no-line text-right">
                                <?php $pervAccount = $invoice->client->due - $invoice->due; ?>
                                {{$pervAccount}}</td>
                                <td class="no-line text-center"><strong>الرصيد الحالى : </strong></td>
                                <td class="no-line text-right">{{$invoice->due + $pervAccount}}</td>
                                </tr>
                                </tbody>
                        </table>
                        <p class="pull-right hideonprint">{{$invoice->note}}<br/><br/>
                            
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(Config::get('custom-setting.InvoiceNotes'))
    <div class="row">
        <div class="col-md-12">
            <p>{!!Config::get('custom-setting.InvoiceNotes')!!}</p>
        </div>
    </div>
    @endif
</div>
<div id="footer">
    <b>{!!Config::get('custom-setting.Address')!!}</b>
</div>
@stop()
