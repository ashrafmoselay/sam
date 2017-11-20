@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row tableData">
        <div class="col-md-12">
            <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <td>الباركود</td>
                    <td>العملية</td>
                </tr>
            </thead>
            <tbody>
                @foreach($codes as $code)
                <tr>
                    <td class="cod{{$code}}">
                        @php 
                        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($code, "C39+",1,33) . '" alt="'.$code.'"   />';
                        @endphp
                        <br/>
                        <span>{{ $code }}</span>
                    </td>
                    <td><button rel="cod{{$code}}" class="btn btn-success printCode">طباعة</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
@stop()

@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on("click",".printCode",function(e){
            e.preventDefault();
            var tdClass = $(this).attr('rel');
            var printContents = $("."+tdClass).html();
            var originalContents = $('.tableData').html();
            $('.tableData').html(printContents);
            window.print();
            $('.tableData').html(originalContents);
        });
    });
</script>
@stop