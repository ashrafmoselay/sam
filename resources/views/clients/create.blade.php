@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
    	<div class="col-md-12">
    		{!! View::make('clients.create_partial')!!}
    	</div>
	</div>
</div>
@stop()

@section('javascript')
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("input",".total,.paid",function(e){
			e.preventDefault();
			var due = parseFloat($(".total").val()) - parseFloat($(".paid").val());
			$(".due").val(due);
		});
	});
</script>
@stop()