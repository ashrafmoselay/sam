{!! Form::open($attr) !!}
	<div class="form-group col-md-6">
		<label for="">الدور</label>
		<input name="name" type="text" value="{{$item->name}}" class="form-control" required="required" placeholder="الدور">
	</div>
	<div class="form-group col-md-6">
		<label for="">اسم الظهور</label>
		<input name="display_name" type="text" value="{{$item->display_name}}" class="form-control" required="required" placeholder="اسم الظهور">
	</div>
	<div class="form-group col-md-12">
		<label for="">الوصف</label>
		<textarea class="form-control" name="description">{{$item->description}}</textarea>
	</div>
	<hr/>
	@foreach($controllers as $cont)
		<div class="row control">
			<div class="form-group col-md-12">
				<label for="">
					<h3>{{$cont}}</h3>
				</label>
					<input type="checkbox" class="select_all">
					Select All
			</div>
			@foreach($methods[$cont] as $k=>$op)
				@php
					$name = $cont.'-'.$op;
				@endphp
				<div class="col-md-6">
					<input @if(isset($rolperm) && in_array($name,$rolperm)) checked="" @endif    type="checkbox" name="perm[{{$name}}]" valu="{{$name}}">
						{{$op}}
				</div>
			@endforeach
		</div>
	@endforeach
	<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
{!! Form::close() !!}
@section('javascript')
<script type="text/javascript">
$(document).ready(function(){
	$('.select_all').change(function() {
	    var checkboxes = $(this).closest('div.control').find(':checkbox');
	    checkboxes.prop('checked', $(this).is(':checked'));
	});
});
</script>
@stop()