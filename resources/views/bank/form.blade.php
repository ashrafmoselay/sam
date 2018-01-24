<div class="form-group">
	<label>اسم البنك</label>
	<input name="name" type="text" value="{{$item->name}}" class="form-control" required="required" >
</div>

<div class="form-group">
	<label>رقم الحساب </label>
	<input name="number" required type="text" value="{{$item->number}}" class="form-control"  >
</div>
<div class="form-group">
	<label>الرصيد الحالى</label>
	<input name="balance" required type="text" value="{{$item->balance}}" class="form-control"  >
</div>
<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>
