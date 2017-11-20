<div style="display: none;" class="cloneDiv">
	<div class="col-md-12 productList">
	  <div class="col-md-3">
	  	<div class="form-group">
			<label for="">الصنف </label>
			<input name="product_id[]" autocomplete="off" class="typeahead form-control" required=""  type="text">
			
		</div>
	  </div>
	  @if(!$item->id)
		<div class="checknew form-group">
			جديد
			<input class="form-control isnew" type="checkbox" name="isnew[]" class="newProd">
		</div>
		@endif
	  <div class="col-md-3">
	  	<div class="form-group">
			<label for="">{{ trans('app.Cost Price') }}</label>
			<input name="cost[]" autocomplete="off" class="form-control cost originalprice" required=""  type="number" step="0.01" min="0">
			
		</div>
	  </div>
	  <div class="col-md-3">
	  	<div class="form-group">
			<label for="">{{ trans('app.Qantity') }} <span class="avilableQty"></span></label>
			<div class="input-group">
			  <input value="1" min="0" type="number" placeholder="{{ trans('app.Qantity') }}" type="text" required="required" name="quantity[]"  class="form-control qty" >
			  <span class="input-group-addon">
			  	@include('unit.dropdown',['selId'=>'','allunit'=>\App\Unit::get()])
			  </span>
			</div>
		</div>
	  </div>
	  <div class="col-md-3">
	  	<div class="form-group">
			<label for="">{{ trans('app.Total') }} </label>
			<input name="totalcost[]" class="form-control total" required="" min="0"  type="number" step="0.01">
			
		</div>
	  </div>
	  <div class="btnx">
			<a class="btn btn-danger btn-sm"  href="#" role="button">-</a>
  			<a class="btn btn-primary addnewproducts btn-sm" href="#" role="button">+</a>
	  </div>
	</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">إضافة مورد جديد</h4>
      </div>
      <div class="modal-body">
        	{!! View::make('suppliers.form')!!}
      </div>
    </div>

  </div>
</div>