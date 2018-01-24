@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			{!! Form::open(['route'=>['category.update',$item->id],'method'=>'put']) !!}
				<div class="form-group col-md-6">
					<label for="">إسم الفئة</label>
					<input name="name" type="text" value="{{$item->name}}" class="form-control" required="required" placeholder="إسم الفئة">
				</div>
				<div class="form-group col-md-6">
					<label for="">النوع</label>
					<select name="type"  class="form-control" required="required">
					<option {{($item->type==1)?'selected=""':''}} value="1">فئة رئيسية</option>
					<option {{($item->type==2)?'selected=""':''}} value="2">فئة فرعية</option>
					
					</select>
    				
				</div>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
				</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@stop()