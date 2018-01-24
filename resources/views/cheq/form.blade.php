
<div class="form-group col-md-12">
	<label>رقم الشيك </label>
	<input name="cheq_num" required type="text" value="{{$item->cheq_num}}" class="form-control"  >
</div>
<div class="form-group col-md-6">
	<label> البنك</label>
	<select required="required" name="bank_id"  class="form-control">
		@foreach(\App\Bank::get() as $cat)
			<option {{($item->bank_id==$cat->id)?'selected=""':''}}  value="{{$cat->id}}">{{$cat->name}}</option>
		@endforeach
	</select>
</div>
<div class="form-group col-md-6">
	<label> المحول اليه</label>
	<select required="required" name="supplier_id"  class="form-control">
		@foreach(\App\Suppliers::get() as $cat)
			<option {{($item->supplier_id==$cat->id)?'selected=""':''}}  value="{{$cat->id}}">{{$cat->name}}</option>
		@endforeach
	</select>
</div>

<div class="form-group col-md-6">
	<label>المبلغ </label>
	<input name="value" required type="number" value="{{$item->value}}" class="form-control"  >
</div>

<div class="form-group col-md-6">
	<label for="">تاريخ الاستحقاق</label>
	<input  name="date" value="{{($item->date)?$item->date:date('Y-m-d')}}"  type="text" class="form-control datepicker"
			placeholder="التاريخ">

</div>
<div class="form-group col-md-12">
	<label for="">  خصم اتوماتيك من حساب البنك والمورد عند الوصول لتاريخ اﻷستحقاق</label>
	<input @if($item->auto) checked="" @endif class="form-control" type="checkbox" name="auto">
</div>
<div class="col-md-12">
	<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
</div>
@section('javascript')
	<script type="text/javascript">
        $(document).ready(function(){
            $('.datepicker').datepicker({format: 'yyyy-mm-dd',rtl: true});
        });
	</script>
@stop()
