@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 filterData">

            <div class="col-md-10">
                <div class="pull-right">
                    <div class="col-md-2">
                        <div class="form-group">
                            <input name="term" type="text" class="search form-control" placeholder="رقم الشيك">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group ">
                            <input name="from" type="text" class="from form-control datepicker" placeholder="{{ trans
                            ('app.From Date') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input name="to" type="text" class="to form-control datepicker" placeholder="{{ trans
                            ('app.To Date') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="supplier_id" data-show-subtext="true" data-live-search="true"   class="form-control supplier_id selectpicker">
                            <option value="">{{trans('app.Suppliers')}}</option>
                            @foreach(\App\Suppliers::get() as $sup)
                                <option value="{{$sup->id}}">{{$sup->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="bank_id" data-show-subtext="true" data-live-search="true"   class="form-control
                         bank_id selectpicker">
                            <option value="">البنك</option>
                            @foreach(\App\Bank::get() as $sup)
                                <option value="{{$sup->id}}">{{$sup->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                <select style="width: 80px;" class="form-control page_size"  name="page_size">
                    <option value=""></option>
                    <option  value="{{$list->total()}}">عرض الكل ( {{$list->total()}} ) </option>
                    <option  value="20">20</option>
                    @for($i=50;$i<=100;$i=$i+50)
                        <option {{(isset($_GET['page_size']) && $_GET['page_size']==$i)?'selected=""':''}} value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="pull-left">
                <a class="btn btn-success" href="{{url('cheq/create')}}" role="button">اضافة شيك</a>
                 </div>
            </div>
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			<div id="list">
                @include('cheq._list')
            </div>

		</div>
	</div>
</div>
@stop()

@section('javascript')
	<script type="text/javascript">
        $(document).ready(function() {
            $('.datepicker').datepicker({format: 'yyyy-mm-dd', rtl: true});
            $(document).on("click", ".changeStatus", function (e) {
                e.preventDefault();
                var elm = $(this);
                var status = elm.attr("status");
                if (status == 0) {
                    status = 1;
                } else {
                    status = 0;
                    swal({
                        title: "خطأ!",
                        text: "لا يمكنك تغيير الحالة لانه تم الخصم بالفعل",
                        type: "error",
                        confirmButtonText:
                            "تمام",
                    });
                    return 0;
                }
                swal({
                        title: "هل أنت متأكد من خصم الشيك ؟ ",
                        text: "سوف يتم خصم قيمة الشيك من الحساب البنكى ومن رصيد المورد",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD4140",
                        closeOnConfirm: true,
                        showLoaderOnConfirm: true,
                        cancelButtonText: "إلغاء",
                        confirmButtonText: "نعم متأكد",
                    },
                    function () {
                        var url_ = "{{url('cheq/changeStatus')}}";
                        $.ajax({
                            url: url_,
                            type: 'post',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                status: status,
                                elmid: elm.attr("rel")
                            },
                            success: function (result) {
                                if (result) {
                                    elm.attr('status', status);
                                    elm.find('img').attr('src', '{{asset('icon')}}/' + status + '.png');
                                }
                            }
                        });
                    });
            });
            $(document).on("input",".search",function(e){
                e.preventDefault();
                var url_ = "{{url('cheq/index')}}";
                $.ajax({
                    url:url_,
                    type:'GET',
                    data:$(".filterData input,select").serialize(),
                    success:function(result){
                        $("#list").html(result);
                    }
                });
            });
            $(document).on("change",".from,.to,select",function(e){
                e.preventDefault();
                var url_ = "{{url('cheq/index')}}";
                $.ajax({
                    url:url_,
                    type:'GET',
                    data:$(".filterData input,select").serialize(),
                    success:function(result){
                        $("#list").html(result);
                    }
                });
            });
            $(document).on("change",".page_size",function(e){
                e.preventDefault();
                var url_ = "{{url('cheq/index')}}";
                $.ajax({
                    url:url_,
                    type:'GET',
                    data:$(".filterData input,select").serialize(),
                    success:function(result){
                        $("#list").html(result);
                        $(".page_size").val(page_size);
                    }
                });
            });
        });

</script>
@stop()