@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row"> 	 
		<div class="col-md-12">
				<form class="form-inputs" id="setting-form" action="{{ route('setting.edit') }}" method="post">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-group">

								@foreach($setting as $key=>$value)
						        <div class="form-group col-md-{{($key=='InvoiceNotes')?12:4}}">
						        	<label>{{trans('app.'.$key)}} :</label>
						        	@if($key=='show_cost_price')
							        	<select class="form-control" name="{{$key}}">
							        		<option  {{($value==1)?'selected=""':''}}  value="1">نعم</option>
							        		<option {{($value==2)?'selected=""':''}} value="2">لا</option>
							        	</select>
						        	@elseif($key=="background")
						        		<div id="cp2" class="input-group colorpicker-component ">
										    <input type="text" name="{{$key}}" value="{{old($key,$value)}}" class="form-control" />
										    <span class="input-group-addon"><i></i></span>
										</div>

						        	@elseif($key=="size")
							        	<select class="form-control" name="{{$key}}">
							        		<option  {{($value==12)?'selected=""':''}}  value="12">12</option>
							        		<option {{($value==13)?'selected=""':''}} value="13">13</option>
							        		<option {{($value==14)?'selected=""':''}} value="14">14</option>
							        		<option {{($value==15)?'selected=""':''}} value="15">15</option>
							        		<option {{($value==16)?'selected=""':''}} value="16">16</option>
							        		<option {{($value==18)?'selected=""':''}} value="18">18</option>
							        		<option {{($value==20)?'selected=""':''}} value="20">20</option>
							        	</select>

						        	@elseif($key=="PrintSize")
							        	<select class="form-control" name="{{$key}}">
							        		<option  {{($value==12)?'selected=""':''}}  value="12">12</option>
							        		<option {{($value==13)?'selected=""':''}} value="13">13</option>
							        		<option {{($value==14)?'selected=""':''}} value="14">14</option>
							        		<option {{($value==15)?'selected=""':''}} value="15">15</option>
							        		<option {{($value==16)?'selected=""':''}} value="16">16</option>
							        		<option {{($value==18)?'selected=""':''}} value="18">18</option>
							        		<option {{($value==20)?'selected=""':''}} value="20">20</option>
							        	</select>

						        	@elseif($key == "use_barcode")
							        	<select class="form-control" name="{{$key}}">
							        		<option  {{($value==1)?'selected=""':''}}  value="1">نعم</option>
							        		<option {{($value==2)?'selected=""':''}} value="2">لا</option>
							        	</select>
						        	@elseif($key == "show_profit")
							        	<select class="form-control" name="{{$key}}">
							        		<option  {{($value==1)?'selected=""':''}}  value="1">نعم</option>
							        		<option {{($value==2)?'selected=""':''}} value="2">لا</option>
							        	</select>
						        	@elseif($key != "InvoiceNotes")
						        	<input class="form-control" type="text" name="{{$key}}" value="{{old($key,$value)}}">
						        	@else
						        	<textarea id="note" name="{{$key}}" class="form-control" name="{{$key}}">{{old($key,$value)}}</textarea>
						        	@ckeditor('note')

						        	@endif
						        </div>
						        @endforeach
							</div>
							<button type="submit" class="btn btn-primary btn-block">{{trans('app.Submit')}}</button>
				</form>
			</div>
			<!-- form -->
		</div>
		<!-- forms div -->
	</div>
@stop
@section('javascript')
   <script>CKEDITOR.dtd.$removeEmpty['span'] = false;</script>
@stop