<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <td>المورد</td>
            <td>التفاصيل</td>
        </tr>
    </thead>
		@foreach($list as $item)
			<tr>  
                <td>
                    <table class="table">
                        <tr>
                            <td>المورد</td>
                            <td> {{ $item->supplier->name }} </td>
                        </tr>
                        <tr>
                            <td>المخزن</td>
                            <td> {{ $item->store->address }} </td>
                        </tr>
                        <tr>
                            <td>الأجمالى</td>
                            <td> {{ $item->total }} </td>
                        </tr>
                        <tr>
                        <tr>
                            <td>
                            خصم قيمة المرتجعات من المورد
                            </td>
                            <td>{{($item->is_subtract)?'نعم':'لا'}}</td>
                        </tr>
                        <tr>
                            <td>التاريخ</td>
                            <td> {{ $item->created_at }} </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-hover">
                        <thead>
                            <tr class="active">
                                <th>{{ trans('app.ID') }}</th>
                                <th>{{ trans('app.Products') }}</th>
                                <th>{{ trans('app.Cost Price') }}</th>
                                <th>{{ trans('app.Qantity') }}</th>
                                <th>الوحدة</th>
                                <th>{{ trans('app.Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($item->details as $key=>$prod)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$prod->product->title}}</td>
                            <td>{{$prod->cost}}</td>
                            <td>{{$prod->qty}}</td>
                            <td>{{$prod->unit->title}}</td>
                            <td>{{$prod->total}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>    
                </td>         
            </tr>
		@endforeach
</table>
 <div class="row text-center">
 	{!! $list->appends(\Request::except('page'))->render() !!}
 </div>
