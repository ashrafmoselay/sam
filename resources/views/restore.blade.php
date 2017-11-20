@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			<form enctype="multipart/form-data" action="{{ url('restore') }}" method="post">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group">
					<label for="">نسخة البيانات</label>
					<input required="" name="file" type="file" class="form-control" >
				</div>
			</div>
			<div class="submit">
			    <input class="btn btn-block btn-primary" type="submit" value="تحميل">
			</div>
		</form>
	</div>
</div>
@stop()
