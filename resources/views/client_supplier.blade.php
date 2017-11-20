@extends('layouts.app')
@section('content')
<style type="text/css">
    .col-md-6.scroll {
        max-height: 400px;
        height: 400px;
        overflow-x: auto;
        margin-bottom: 20px;
    }
</style>
<div class="container">
    <div class="row hideonprint">
        <div class="col-md-12"> 
                <div class="form-group col-md-3">
                    <input type="text" class="fromdate form-control datepicker" placeholder="{{ trans('app.From Date') }}">
                </div>
                <div class="form-group col-md-3"> 
                    <input type="text" class="todate form-control datepicker" placeholder="{{ trans('app.To Date') }}">
                </div>
                <div class="form-group col-md-5"> 
                <select name="client" data-show-subtext="true" data-live-search="true"   class="form-control client selectpicker">
                    <option value="">عميل ومورد</option>
                    @foreach(\App\Clients::get() as $client)
                        <option value="{{$client->id}}">{{$client->name}}</option>
                    @endforeach
                </select>
                </div>
                <div class="form-group pull-left col-md-1">
                    <a class="btn btn-success print-window" href="#" role="button">{{ trans('app.Print') }}</a>
                </div>
        </div>
    </div>
    <div id="list" class="row">

    </div>
</div>
@stop
@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on("change",".fromdate,.todate,.client",function(e){
            e.preventDefault();
            var from = $(".fromdate").val();
            var to = $(".todate").val();
            var client = $(".client").val();
            var url_ = "{{url('clientsupplier')}}";
            $.ajax({
                url:url_,
                type:'GET',
                data:{from:from,to:to,client:client},
                success:function(result){
                    $("#list").html(result);
                }
            });
        });
    });
</script>
@stop()