@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group pull-left">
                <a class="btn btn-success print-window" href="#" role="button">{{ trans('app.Print') }}</a>
            </div>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <td colspan="7">{{isset($list[0]->bank->name)?$list[0]->bank->name:''}}</td>
                    </tr>
                    <tr>
                        <td class="text-center">م</td>
                        <td class="text-center"><strong>التاريخ</strong></td>
                        <td class="text-center"><strong>البيان</strong></td>
                        <td class="text-center"><strong>نوع العملية</strong></td>
                        <td class="text-center"><strong>الرصيد قبل</strong></td>
                        <td class="text-center"><strong>المبلغ</strong></td>
                        <td class="text-center"><strong>الرصيد بعد</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <!-- foreach ($order->lineItems as $line) or some such thing here -->
                    @foreach($list as $key=>$val)
                    <tr>
                        <td class="text-center">{{$key+1}}</td>
                        <td class="text-center">{{$val->op_date}}</td>
                        <td class="text-center">{{$val->note}}</td>
                        <td class="text-center">{{$val->type}}</td>
                        <td class="text-center">{{$val->total}}</td>
                        <td class="text-center">{{$val->value}}</td>
                        <td class="text-center">{{$val->due}}</td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>

            <div class="row text-center">
            {!! $list->render()  !!}
            </div>
        </div>
    </div>
</div>
@stop