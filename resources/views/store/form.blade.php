<div class="form-group">
	<label>اسم المخزن</label>
	<input name="address" type="text" value="{{$item->address}}" class="form-control" required="required" >
</div>
<div class="form-group">
	<label>الموبيل</label>
	<input name="mobile" type="text" value="" class="form-control"  >
</div>
<div class="form-group">
	<label>ملاحظات</label>
	<textarea class="form-control" name="note">{{$item->note}}</textarea>
</div>
<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>