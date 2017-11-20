<style type="text/css">
	.unitclass{
	    width: 100%;
    	border: none;
    	background: transparent;
    	padding: 0 8px;
		outline: 0;
	}
	.input-group-addon:last-child {
	    padding: 0;
	    min-width: 97px;
	}
</style>
<select required="" name="store_unit[]" class="unitclass" selUnit = "{{isset($selId)?$selId:''}}">
	@foreach($allunit as $unit)

	<option @if(isset($selId)&&$selId==$unit->id) selected="" @endif value="{{$unit->id}}">{{$unit->title}}</option>

	@endforeach
</select>