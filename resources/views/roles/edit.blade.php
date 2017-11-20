@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			@include('roles.form',['attr'=>['route'=>['role.update',$item->id],'method'=>'put']])
		</div>
	</div>
</div>
@stop()