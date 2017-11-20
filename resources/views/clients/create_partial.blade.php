
{!! Form::open(['route'=>'clients.store','method'=>'post']) !!}
	<div class="form-group col-md-12">
		<label for="">{{ trans('app.Client Name') }}</label>
		<input name="name" type="text" class="form-control" required="required" placeholder="{{ trans('app.Client Name') }}">
	</div>
	<div class="form-group col-md-4">
		<label for="">نوع العميل</label>
		<select class="form-control" name="type">
			<option value="1">قطاعى</option>
			<option value="2">جملة</option>
			<option value="3">جملة الجملة</option>
		</select>
	</div>
	<div class="form-group col-md-4">
		<label for="">{{ trans('app.Mobile') }}</label>
		<input name="mobile" type="text" class="form-control"  placeholder="{{ trans('app.Mobile') }}">
	</div>
	<div class="form-group col-md-4">
		<label for="">{{ trans('app.qest_value') }}</label>
		<input name="qest_value"  type="text" class="form-control"  placeholder="{{ trans('app.qest_value') }}">
	</div>
	
	<div class="form-group col-md-4">
		<label for="">{{ trans('app.Total') }}</label>
		<input name="total" min="0" type="number" step="0.01" class="form-control total" value="0" required="required" placeholder="{{ trans('app.Total') }}">
		
	</div>
	<div class="form-group col-md-4">
		<label for="">{{ trans('app.Paid') }}</label>
		<input name="paid" min="0" type="number" step="0.01" class="form-control paid" value="0" required="required" placeholder="{{ trans('app.Paid') }} ">
	</div>
	<div class="form-group col-md-4">
		<label for="">{{ trans('app.Due') }}</label>
		<input name="due" readonly="" type="number" step="0.01" class="form-control due" value="0" required="required" placeholder="{{ trans('app.Due') }}">
		
	</div>
	<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
{!! Form::close() !!}