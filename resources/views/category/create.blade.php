@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>'category.store','method'=>'post']) !!}
				<div class="form-group col-md-6">
					<label for="">إسم الفئة</label>
					<input name="name" type="text" class="form-control" required="required" placeholder="إسم الفئة">
				</div>
				<div class="form-group col-md-6">
					<label for="">النوع</label>
					<select name="type"  class="form-control" required="required">
					<option value="1">فئة رئيسية</option>
					<option value="2">فئة فرعية</option>
					
					</select>
    				
				</div>
				<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@stop()
