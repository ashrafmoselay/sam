<option value="">إختر المورد</option>
@foreach(\App\Suppliers::get() as $sup)
	<option value="{{$sup->id}}">{{$sup->name}} {{"( رصيد المورد $sup->due ج.م )"}}</option>
@endforeach