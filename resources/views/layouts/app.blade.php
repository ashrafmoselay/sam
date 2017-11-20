<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

     <title>{{Config::get('custom-setting.SiteName')}}</title>
    <!-- Styles -->
    <link href='{{asset('css')}}/droidarabickufi.css' rel='stylesheet' type='text/css'/>

    <link href="{{asset('css')}}/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('css')}}/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{asset('css')}}/bootstrap-select.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css')}}/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="{{asset('css')}}/bootstrap-rtl.css">
    <link rel="stylesheet" href="{{asset('vendor/ckeditor/plugins/fontawesome/font-awesome/css')}}/font-awesome.min.css">
    <link href="{{ asset("/bower_components/sweetalert/dist/sweetalert.css")}}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" type="image/x-icon" href="/icon/favicon.ico">
    @yield('style')

    <style type="text/css">
        .input-group-addon:last-child {
            font-size: 11px;
            padding: 6px;
            font-weight: bold;
        }
        body{
            background-color: {{\config('custom-setting.background')}};
            font-weight: bold;
        }       
        @media print {
          @page { margin: .3cm; }
          body { margin: .2cm;}
        }
        @media print {
          a[href]:after {
            content: none !important;
          }
        }
        #myModalDeveloper table td {
            vertical-align: middle !important;
        }
        #myModalDeveloper table tr td:first-child {
            font-weight: bold;
        }

        .colorpicker-2x .colorpicker-saturation {
            width: 200px;
            height: 200px;
        }
        
        .colorpicker-2x .colorpicker-hue,
        .colorpicker-2x .colorpicker-alpha {
            width: 30px;
            height: 200px;
        }
        
        .colorpicker-2x .colorpicker-color,
        .colorpicker-2x .colorpicker-color div {
            height: 30px;
        }
        ul.typeahead.dropdown-menu>li>a {
            white-space: normal!important;
        }
        .has-error .selectpicker {
             border-color: #a94442 !important;
             border-width: 1px;
             background-color: #f2dede;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
        }
        body {
            font-size: {{\config('custom-setting.size')}}px !important;
        }

        @media print {
          body { 
            font-size: {{\config('custom-setting.PrintSize')}}px !important;
            }

        }
    </style>
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a style="font-size: 20px;color: #000;" class="navbar-brand" href="{{ url('/home') }}">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        {{\Config::get('custom-setting.SiteName')}}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    @if (!Auth::guest())
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">

                        <?php 
                            $active = '';
                            if(Request::is('store') || Request::is('category') || Request::is('products') || Request::is('report') || Request::is('transit')|| Request::is('unit')){
                                $active = 'active';
                            }
                        ?>
                        <li class="dropdown {{$active}}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-book" aria-hidden="true"></i>
                             اﻷصناف <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">

                                <li>
                                    <a href="{{ url('/store') }}">
                                        <i class="fa fa-home" aria-hidden="true"></i>
                                        المخازن
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/unit') }}">
                                        <i class="fa fa-cubes" aria-hidden="true"></i>
                                        الوحدات
                                    </a>
                                </li>
                                <li @if(Request::is('category')) class='active' @endif>
                                <a href="{{ url('/category') }}"><i class="fa fa-th-large" aria-hidden="true"></i>
        {{ trans('app.category') }}</a>
                                </li>
                                <li @if(Request::is('products')) class='active' @endif>
                                    <a href="{{ url('/products') }}">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    المنتجات
                                    </a>
                                </li>
                                <li @if(Request::is('transit')) class='active' @endif>
                                    <a href="{{ url('/transit') }}">
                                    <i class="fa fa-exchange" aria-hidden="true"></i>
                                    تحويل كميات
                                    </a>
                                </li>
                            
                            <li>
                                <a href="{{url('report')}}">
                                <i class="fa fa-file-o" aria-hidden="true"></i>
                                تقرير ملخص</a>
                            </li>
                            

                            </ul>
                        </li>
                        <?php 
                            $active = '';
                            if(Request::is('purchaseInvoice') || Request::is('orders') || Request::is('purchaseInvoice/allDetailes') || Request::is('orders/allDetailes')){
                                $active = 'active';
                            }
                        ?>
                        <li class="dropdown {{$active}}">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                                 الفواتير <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ url('/purchaseInvoice') }}">
                                        <i class="fa fa-arrow-circle-down" aria-hidden="true"></i>

                                    {{ trans('app.Purchase Invoice') }}</a></li>
                                    <li><a href="{{ url('/orders') }}">
                                    <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>

                                    {{ trans('app.Orders') }}</a></li>
                                    <li><a href="{{ url('/purchaseInvoice/allDetailes') }}">
                                    <i class="fa fa-list-alt" aria-hidden="true"></i>

                                        تفاصيل المشتريات
                                    </a></li>
                                    <li><a href="{{ url('/orders/allDetailes') }}">
                                    <i class="fa fa-print" aria-hidden="true"></i>

                                        تفاصيل المبيعات
                                    </a></li>
                                    <li><a href="{{ url('returns') }}">
                                    <i class="fa fa-rotate-right" aria-hidden="true"></i>
                                        مرتجعات المشتريات
                                        
                                    </a></li>
                                    <li><a href="{{ url('ordersreturns') }}">
                                    <i class="fa fa-rotate-left" aria-hidden="true"></i>
                                        مرتجعات المبيعات
                                        
                                    </a></li>
                                    <li><a href="{{ url('daily') }}">
                                    <i class="fa fa-calendar " aria-hidden="true"></i>
                                        كشف اليومية
                                        
                                    </a></li> 
                                </ul>
                        </li>

                        <?php 
                            $active = '';
                            if(Request::is('users') || Request::is('role')){
                                $active = 'active';
                            }
                        ?>
                         <li class="dropdown {{$active}}">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-user" aria-hidden="true"></i>

                                     المستخدمين  <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu"> 
                                    <li>
                                        <a href="{{ url('/users') }}">
                                        <i class="fa fa-user {{$active}}"></i>
                                        المستخدمين
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/role') }}">
                                        <i class="fa fa-key {{$active}}"></i>
                                        صلاحيات
                                        </a>
                                    </li>
                                </ul>
                        </li>
                        
                        <?php 
                            $active = '';
                            if(Request::is('clients') || Request::is('clientsupplier') || Request::is('clients/payments') ){
                                $active = 'active';
                            }
                        ?>
                        
                        
                         <li class="dropdown {{$active}}">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-users" aria-hidden="true"></i>

                                     {{ trans('app.Clients') }}  <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ url('/clients') }}">
                                        <i class="fa fa-users" aria-hidden="true"></i>

                                    {{ trans('app.Clients') }}</a></li>
                                    <li><a href="{{ url('/clients/payments') }}">
                                        <i class="fa fa-money" aria-hidden="true"></i>

                                    {{ trans('app.Clients Payments') }}</a></li>
                                    <li><a href="{{ url('clientsupplier') }}">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            كشف حساب مجمع عميل ومورد
                                        </a>
                                    </li>


                                </ul>
                        </li>


                        <?php 
                            $active = '';
                            if(Request::is('suppliers') || Request::is('suppliers/payments')){
                                $active = 'active';
                            }
                        ?>
                         <li class="dropdown {{$active}}" >
                                <a  href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-truck" aria-hidden="true"></i>


                                     {{ trans('app.Suppliers') }}  <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ url('/suppliers') }}">
                                        <i class="fa fa-truck" aria-hidden="true"></i>

                                    {{ trans('app.Suppliers') }}</a></li>
                                    <li><a href="{{ url('/suppliers/payments') }}">

                                        <i class="fa fa-money" aria-hidden="true"></i>
                                    {{ trans('app.Suppliers Payments') }}</a></li>
                                    <li><a href="{{ url('clientsupplier') }}">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            كشف حساب مجمع عميل ومورد
                                        </a>
                                    </li>
                                </ul>
                        </li>
                        <?php 
                            $active = '';
                            if(Request::is('treasury') || Request::is('treasuryMovement') || Request::is('treasury') || Request::is('masrofat')){
                                $active = 'active';
                            }
                        ?>
                         
                     <li class="dropdown {{$active}}" >
                            <a  href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-money" aria-hidden="true"></i>


                                 المالية  <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                        <li @if(Request::is('treasury')) class='active' @endif>
                        <a href="{{ url('/treasury') }}">
                            <i class="fa fa-money" aria-hidden="true"></i>

                        {{ trans('app.treasuryMovement') }}</a></li>
                        <li @if(Request::is('masrofat')) class='active' @endif>
                        <a href="{{ url('/masrofat') }}">
                        <i class="fa fa-exchange" aria-hidden="true"></i>

                        {{ trans('app.Masrofat') }}</a></li>
                        </ul>
                        
                         
                        <li @if(Request::is('shoraka')) class='active' @endif>
                        <a href="{{ url('/shoraka') }}">
                            <i class="fa fa-diamond" aria-hidden="true"></i>

                        {{ trans('app.Shoraka') }}</a></li>

                        

                        <?php 
                            $active = '';
                            if(Request::is('setting') || Request::is('downloaddb')){
                                $active = 'active';
                            }
                        ?>
                         <li class="dropdown {{$active}}" >
                                <a  href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-cogs" aria-hidden="true"></i>


                                     {{ trans('app.Setting') }}  <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">

                                <li @if(Request::is('setting')) class='active' @endif>
                                <a href="{{ url('/setting') }}">
                                    <i class="fa fa-cogs" aria-hidden="true"></i>

                                {{ trans('app.Setting') }}</a></li>
                                <li>
                                <a href="{{ url('downloaddb') }}">
                                    <i class="fa fa-database" aria-hidden="true"></i>
                                    تحميل نسخة من البيانات
                                </a>
                                </li>
                                <li>
                                <a href="{{ url('restore') }}">
                                    <i class="fa fa-database" aria-hidden="true"></i>
                                    استرجاع البيانات
                                </a>
                                </li>
                                 
                                <li @if(Request::is('closeYear')) class='active' @endif>
                                <a class="closeYear" href="{{ url('closeYear') }}">
                                    <i class="fa fa-copy" aria-hidden="true"></i>
                                    التقفيل السنوى
                                </a>
                                </li>
                                
                            </ul>
                        <li><a href="#" data-toggle="modal" data-target="#myModalDeveloper"><i class="fa fa-info-circle" aria-hidden="true"></i>
المبرمج</a></li>
                    </ul>
                    @endif
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">{{ trans('app.Login') }}</a></li>
                        @else
                            <li>
                                <a title="خروج" href="{{ url('/logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                             <i class="fa fa-sign-out" aria-hidden="true"></i>
                                    
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                            
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    <div  id="myModalDeveloper" class="modal fade" role="dialog">
      {!! base64_decode(file_get_contents(public_path().'/dd.php'))!!}
    </div>
    </div>

    <!-- Scripts -->
    <!-- JavaScripts -->
    <script src="{{ asset ("/js/jquery-2.2.3.min.js") }}"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ asset ("/js/bootstrap.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/js/bootstrap3-typeahead.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/js/bootstrap-datepicker.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/js/bootstrap-select.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/js/bootstrap-colorpicker.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/js/validator.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/sweetalert/dist/sweetalert.min.js") }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function(){   
            
            $('.datepicker').datepicker({format: 'yyyy-mm-dd',rtl: true}); 
            $('form').validator(); 
            $('.selectpicker').selectpicker();
            $('#cp2').colorpicker({
                container: true,
                showPicker:true,
                customClass: 'colorpicker-2x',
                sliders: {
                    saturation: {
                        maxLeft: 200,
                        maxTop: 200
                    },
                    hue: {
                        maxTop: 200
                    },
                    alpha: {
                        maxTop: 200
                    }
                }
            });
            $('.datepicker').datepicker({format: 'yyyy-mm-dd',rtl: true}); 

            $('form').on('submit', function(e) {
                //e.preventDefault();
                var $btn = $(this).find(':submit');
                $btn.attr('data-loading-text',"<i class='fa fa-circle-o-notch fa-spin'></i> جارى الحفظ ...");
                  $btn.button('loading');
                    setTimeout(function() {
                       $btn.button('reset');
                   }, 4000);
            });
            $('.print-window').click(function() {
                $(this).hide();
                $('.pagination').hide();
                $('.hideonprint').hide();
                $('.col-md-6.scroll').removeClass('scroll');
                window.print();
                $(this).show();
                $('.pagination').show();
                $('.hideonprint').show();
            });
            $( document ).on( 'focus', ':input', function(){
                $( this ).attr( 'autocomplete', 'off' );
            });


            $(document).on("click",".closeYear",function(e){
                e.preventDefault();
                var url_ = $(this).attr('href');
                    swal({title: "هل أنت متأكد من هذه العمليه ؟ ",
                     text: "سوف تفقد البيانات ولن تستطيع استراجعها",
                     type: "warning",
                     showCancelButton: true,
                     confirmButtonColor: "#DD4140",
                     closeOnConfirm: true,
                     showLoaderOnConfirm: true,
                     cancelButtonText: "إلغاء",      
                     confirmButtonText: "نعم متأكد", 
                  },
                  function(){   
                        window.location  = url_;
                  });
            });
        });
    </script>
    @yield('javascript')
</body>
</html>
