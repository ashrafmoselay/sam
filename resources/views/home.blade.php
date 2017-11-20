@extends('layouts.app')

@section('content')
<style type="text/css">
    .tooltip-inner {
        max-width: 100%;
    }
    .shortlink{
        border: 1px solid;
        padding: 2px;
        margin: 10px;
    }
</style>
<div class="container">
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-2 text-center shortlink">
            <a data-html="true" data-toggle="tooltip" title="اﻷضناف" href="{{ url('/products') }}">
                <img src="{{asset('icon')}}/products.png">  
            </a>
            <a href="{{ url('/products/create') }}" class="btn btn-success btn-block">إضافة صنف</a>
        </div>
        <div class="col-md-2 text-center shortlink">
            <a data-html="true" data-toggle="tooltip" title="العملاء" href="{{ url('/clients') }}">
                <img src="{{asset('icon')}}/clients.png">  
            </a>
            <a href="{{ url('/clients/create') }}" class="btn btn-danger btn-block">إضافة عميل</a>
        </div>
        <div class="col-md-2 text-center shortlink">
            <a data-html="true" data-toggle="tooltip" title="المبيعات" href="{{ url('/orders') }}">
                <img src="{{asset('icon')}}/orders.png">  
            </a>
            <a href="{{ url('/orders/create') }}" class="btn btn-primary btn-block">إضافة فاتورة مبيعات</a>
        </div>
        <div class="col-md-2 text-center shortlink">
            <a data-html="true" data-toggle="tooltip" title="الموردين" href="{{ url('/suppliers') }}">
                <img src="{{asset('icon')}}/supplier.png">  
            </a>
            <a href="{{ url('/suppliers/create') }}" class="btn btn-success btn-block">إضافة مورد</a>
        </div>
        <div class="col-md-2 text-center shortlink">
            <a data-html="true" data-toggle="tooltip" title="المشتريات" href="{{ url('/purchaseInvoice') }}">
                <img src="{{asset('icon')}}/purchase.png">  
            </a>
            <a href="{{ url('/purchaseInvoice/create') }}" class="btn btn-warning btn-block">إضافة فاتورة مشتريات</a>
        </div> 
    </div>
    <div class="row">
        <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
          @if(Session::has('alert-' . $msg))

          <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
          @endif
        @endforeach
      </div> <!-- end .flash-message -->
        
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