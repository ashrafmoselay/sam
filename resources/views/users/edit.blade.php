@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['route'=>['users.update',$user->id],'method'=>'put']) !!}
                @include('users._form')
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop()