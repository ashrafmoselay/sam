<div @if(!isset($unitItem) && !isset($first)) style="display: none;" @endif  class="cloneDiv">
<div class="row unitItem">	
	<div class="col-md-2">
		<div class="form-group">
			<label for="">الوحدة</label>
			<select name="unit[]" required="" class="form-control">
				@foreach($allunit as $unit)
				<option @if(isset($unitItem) && $unitItem->unit_id==$unit->id) selected="" @endif value="{{$unit->id}}">{{$unit->title}}</option>

				@endforeach
			</select>
		</div>
	</div>
	<div class="col-md-1">
		<div class="form-group">
			<label for="">العدد</label>
			<input required="" type="text" name="pices_num[]" class="form-control" value="{{(isset($unitItem))?$unitItem->pieces_num:''}}">
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label for="">سعر الشراء</label>
			<input required="" min="0" type="number" step="0.01" name="unit_cost[]" class="form-control" value="{{(isset($unitItem))?$unitItem->cost_price:''}}">
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label for="">سعر البيع</label>
			<input required="" min="0" type="number" step="0.01" name="unit_price[]" class="form-control" value="{{(isset($unitItem))?$unitItem->sale_price:''}}">
		</div>
	</div>
	<div class="form-group col-md-2">
		<label for="">سعر البيع الجملة</label>
		<input name="price2[]" min="0" type="number" step="0.01"  class="form-control" value="{{(isset($unitItem))?$unitItem->price2:''}}">
		
	</div>
	<div class="form-group col-md-2">
		<label for="">سعر البيع جملة الجملة</label>
		<input name="price3[]" min="0" type="number" step="0.01"  class="form-control" value="{{(isset($unitItem))?$unitItem->price3:''}}">
		
	</div>
	<div class="col-md-2 hide">
		<div class="form-group">
			<label for="">إفتراضى للبيع</label>
			<input @if(isset($unitItem) && $unitItem->default_sale) checked="" @endif type="checkbox" name="default_sale[{{isset($vv)?$vv:''}}]" class="form-control defsale">
		</div>
	</div>
	<div class="col-md-2 hide">
		<div class="form-group">
			<label for="">إفتراضى للشراء</label>
			<input @if(isset($unitItem) && $unitItem->default_purchase) checked="" @endif type="checkbox" name="default_purchase[{{isset($vv)?$vv:''}}]" class="form-control defpur">
		</div>
	</div>
  <div style="margin-top: 32px;" class="col-md-1">
		<a class="btn btn-danger btn-sm"  href="#" role="button">-</a>
  </div>
</div>
</div>