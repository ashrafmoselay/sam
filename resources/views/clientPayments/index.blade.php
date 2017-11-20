@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
	    <div class="flash-message">
	    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
	      @if(Session::has('alert-' . $msg))

	      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
	      @endif
	    @endforeach
	  </div> <!-- end .flash-message -->
        <div class="col-md-12">
   			<div class="row pull-right hideonprint">
			  <div class="col-md-3">
			  	<div class="form-group ">
					<input type="text" class="fromdate form-control datepicker" placeholder="{{ trans('app.From Date') }}">
				</div>
			  </div>
			  <div class="col-md-3">
			  	<div class="form-group"> 
					<input type="text" class="todate form-control datepicker" placeholder="{{ trans('app.To Date') }}">
				</div>
			  </div>
			  <div class="col-md-3">
				<div class="form-group">
					<select name="client" data-show-subtext="true" data-live-search="true"   class="form-control client selectpicker">
					<option value="">{{trans('app.Clients')}}</option>
					@foreach(\App\Clients::get() as $client)
						<option value="{{$client->id}}">{{$client->name}}</option>
					@endforeach
					</select>
				</div>
				</div>
				<div class="form-group col-md-3">
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
       		<div class="form-group pull-left hideonprint">
			    <a target="_blank" class="btn btn-info printpdf" href="{{url('printPdf','ClientPayments')}}" role="button">حفظ</a>
                <a class="btn btn-success print-window" href="#" role="button">{{ trans('app.Print') }}</a>
			</div>
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
			<div id="list">
				{!! View::make('clientPayments._list',compact('list'))!!}
			 </div>
		</div>
	</div>
</div>
@stop()


@section('javascript')
<script type="text/javascript">
	$(document).ready(function(){
		$('.datepicker').datepicker({format: 'yyyy-mm-dd',rtl: true});
		$(document).on("change",".fromdate,.todate,.client",function(e){
			e.preventDefault();
			var from = $(".fromdate").val();
			var to = $(".todate").val();
			var client = $(".client").val();
			var url_ = "{{url('clients/payments')}}";
			$.ajax({
				url:url_,
				type:'GET',
				data:{from:from,to:to,client:client},
				success:function(result){
					appendParm();
					$("#list").html(result);
				}
			});
		});
		$(document).on("change",".page_size",function(e){
			e.preventDefault();
			var page_size = $(".page_size").val();
			var url_ = "{{url('clients/payments')}}";
			$.ajax({
				url:url_,
				type:'GET',
				data:{page_size:page_size},
				success:function(result){
					 appendParm();
					$("#list").html(result);
					$(".page_size").val(page_size);
				}
			});
		});
	});
	function appendParm(){
		var from = $(".fromdate").val();
		var to = $(".todate").val();
		var newhref = "{{url('printPdf','ClientPayments')}}"+"?client_id="+$(".client").val()+"&from="+from+"&to="+to;
		$(".printpdf").attr('href',newhref);
	}
</script>
@stop()