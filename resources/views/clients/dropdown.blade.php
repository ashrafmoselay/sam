<option value="">--- Select Client ---</option>
@foreach(\App\Clients::get() as $client)
	<option balance="{{$client->due}}"  rel="{{$client->type}}" value="{{$client->id}}">{{$client->name}}</option>
@endforeach