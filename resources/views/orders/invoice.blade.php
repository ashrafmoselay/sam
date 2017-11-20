@extends('layouts.app')
@section('content')
<style type="text/css">
    .invoice-title h2, .invoice-title h3 {
    display: inline-block;
}
.table > tbody > tr > .no-line {
    border-top: none;
}

.table > thead > tr > .no-line {
    border-bottom: none;
}

.table > tbody > tr > .thick-line {
    border-top: 2px solid;
}
#footer{
    width: 100%;
    visibility: hidden;
     position: fixed;
     bottom: 0px;
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
        <div class="col-xs-6">
          <h1>
            {{Config::get('custom-setting.SiteName')}}
          </h1>
        </div>
        <div class="col-xs-6 text-right">
          <h3>فاتورة مبيعات</h3>
          <h3><small>رقم الفاتورة : {{$invoice->id}}</small></h3>
        </div>

        <div class="pull-left">
            <a class="btn print-window" href="#" role="button">
            <img title="{{ trans('app.Print') }}" src="/print.png">
            </a>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <p>
                اسم العميل : {{$invoice->client->name}} <br>
                كود العميل : {{$invoice->client->id}} <br>
                نوع الفاتورة : {{$invoice->payment_type}} <br>
                التاريخ : {{$invoice->created_at}}
              </p>
            </div>
          </div>
        </div>
      </div>
      <!-- / end client details section -->
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
              @foreach($invoice->details as $key=>$prod)
              <tr>
                  <td class="text-center">{{$key+1}}</td>
                  <td>{{$prod->product->title}}</td>
                  <td class="text-center">{{$prod->qty}}</td>
                  <td class="text-center">{{$prod->price}}</td>
                  <td class="text-center">{{$prod->total}}</td>
              </tr>
              @endforeach
              
          </tbody>
      </table>
      <div class="row text-right">
        <div class="col-xs-2 col-xs-offset-8">
          <p>
            <strong>
            إجمالى الفاتورة :  <br>
            المبلغ المدفوع :  <br>
            المبلغ المتبقى :  <br>
            </strong>
          </p>
        </div>
        <div class="col-xs-2">
          <strong>
          {{$invoice->total}} <br>
          {{$invoice->paid}} <br>
          {{$invoice->due}} <br>
          </strong>
        </div>
      </div>
    </div>
    @if(Config::get('custom-setting.InvoiceNotes'))
    <div class="row">
        <div class="col-md-12">
            <h4>ملاحظات:</h4>
            <p>{!!Config::get('custom-setting.InvoiceNotes')!!}</p>
        </div>
    </div>
    @endif
</div>
<div id="footer">
    <b>{!!Config::get('custom-setting.Address')!!}</b>
</div>
@stop()
