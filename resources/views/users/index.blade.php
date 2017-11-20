@extends('layouts.app') 
@section('content')
<div class="container">
     
     <div class="page-header">
      <h3>المستخدمين</h3>
    </div>
     
    <div class="row">
        <div class="col-md-12">
            <div class="form-group pull-left">
                <a class="btn btn-success" href="{{url('users/create')}}" role="button">{{ trans('app.Create') }}</a>
            </div>
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                <table id="simple-table" class="table  table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="detail-col">
                                م
                            </th>
                            <th>
                                اﻷسم
                            </th>
                            <th>
                                الايميل
                            </th>
                            <th>
                                الصلاحية
                            </th>
                            <th class="detail-col">العملية</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach( $users as $user )
                        <tr>
                            <td class="center">
                                {{ $user->id }}
                            </td>


                            <td >
                                {{ $user->name }}
                            </td>
                            <td >
                                {{ $user->email }}
                            </td>

                            <td >
                                {{ ($user->role==1)?"مسئول":"مستخدم" }}
                            </td>
                            <td>
                            <a class="btn btn-primary" href="users/{{ $user->id }}/edit" role="button">{{ trans('app.Edit') }}</a>
                            <a class="btn btn-danger" href="users/destroy/{{ $user->id }}" role="button">{{ trans('app.Delete') }}</a> 

                            </td>
                        </tr>
                        @endforeach    

                    </tbody>
                </table>
            </div>
    </div><!-- /.row -->
</div>
@stop
