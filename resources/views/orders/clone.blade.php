

<div style="display: none;" class="row cloneDiv">
	<div class="col-md-12 productList">

	  <?php 
			$stores = \App\Store::get();
		?>
		<div class="form-group col-md-2">
			<label for="">إختر المخزن</label>
			<select name="store_id[]" class="form-control storeName" required="required">
				@foreach($stores as $s)
					<option value="{{$s->id}}">{{$s->address}}</option>
				@endforeach
			</select>
			
		</div>
	  <div class="col-md-2">
	  	<div class="form-group">
			<label for="">الصنف</label>
			<input name="product_id[]" autocomplete="off" class="typeahead form-control" required=""  type="text">
			
		</div>
	  </div>
	  <div class="col-md-2">
	  	<div class="form-group"> 
			<label for="">{{trans('app.Qantity')}}<span class="avilableQty"></span></label>
			<div class="input-group">
			  <input value="1" min="0" type="number" placeholder="{{ trans('app.Qantity') }}" type="text" required="required" name="quantity[]"  class="form-control qty" >
			  <span class="input-group-addon">
			  	@include('unit.dropdown',['selId'=>'','allunit'=>\App\Unit::get()])
			  </span>
			</div>
		</div>
	  </div>
	  <div class="col-md-2 {{(Config::get('custom-setting.show_cost_price')==2)?'hide':''}}">
	  	<div class="form-group">
			<label for="">{{trans('app.Cost Price')}} </label>
			<input readonly="" name="cost[]" class="form-control originalprice" required="" type="text">
		</div>
	  </div>
	  <div class="col-md-2">
	  	<div class="form-group">
			<label for="">{{trans('app.Sale Price')}} </label>
			<input name="price[]" autocomplete="off" class="form-control price" required=""  min="0" type="number" step="0.01" required="">
			
		</div>
	  </div>
	  <div class="col-md-2">
	  	<div class="form-group">
			<label for="">{{trans('app.Total')}} </label>
			<input name="totalcost[]" autocomplete="off" class="form-control total" required="" min="0" type="number" step="0.01">
			
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
        <h4 class="modal-title">{{trans('app.Create New Client')}}</h4>
      </div>
      <div class="modal-body">
        	{!! View::make('clients.create_partial')!!}
      </div>
    </div>

  </div>
</div>