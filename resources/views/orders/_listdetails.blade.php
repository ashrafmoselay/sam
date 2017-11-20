<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <td>الفاتورة</td>
            <td>التفاصيل</td>
        </tr>
    </thead>
		@foreach($list as $item)
			<tr>
                <td>
                    <table class="table">
                        <tr>
                            <td>العميل</td>
                            <td> {{ $item->client->name }} </td>
                        </tr>
                        <tr>
                            <td>نوع الفاتورة</td>
                            <td> {{ $item->payment_type }} </td>
                        </tr>
                        <tr>
                            <td>الأجمالى</td>
                            <td> {{ $item->total }} </td>
                        </tr>
                        <tr>
                            <td>المدفوع</td>
                            <td> {{ $item->paid }} </td>
                        </tr>
                        <tr>
                            <td>المتبقى</td>
                            <td> {{ $item->due }} </td>
                        </tr>
                        <tr>
                            <td>التاريخ</td>
                            <td> {{ $item->created_at }} </td>
                        </tr>
                    </table>
                </td>
                <td>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-left">
                            <a class="hideonprint" target="_blank" href="{{url('printPdf','Orders')}}?invoiceID={{$item->id}}" role="button">
                            <img title="{{ trans('app.Print') }}" alt="print.png" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAA7VBMVEUAAAD///+/v78fHx8kJCTOzs4iIiIeHh7Nzc0eHh4lJSUfHx/Ozs7KysrLy8vOzs7JyckfHx8hISHKysrKysrLy8sjIyPKysrLy8sgICAjIyMiIiLMzMwgICAiIiLLy8vLy8siIiLLy8vLy8vMzMwhISHMzMzLy8shISHKysohISHMzMwhISEgICAhISEhISEhISEiIiIhISEiIiIiIiIiIiIhISEgICAhISEgICAiIiIiIiLMzMwiIiLMzMzKysrKysohISHMzMzMzMwiIiLKysrKysogICDKysrMzMwiIiIiIiIiIiIiIiLMzMwE99FtAAAATXRSTlMAAgQQFRUWGRohIigqLC0vMDg9P0RUV1hZXV5gYGVoaW5wcnZ4eXp9goeJjpCWl5iZpKepqquwvb7FyMnZ5+jq6+zs7e7u9Pj6+/z9/mdK1+QAAADuSURBVDjL1dPZcoIwFIBhl7q0uNLWDdcq1rZUFPeoaKvWNXn/x2kSlCHHGYYbL/yvmMPHTCYJPp/XEn9CiSuQIkKpewSZFgStjAAUDQJN8Q6CkiSp10Cl4yAHU0wz4r9C8T6bTtj7yIk94u+aUIcPj2EKStglthDDDXQpGLqB4Y3BYbvtX8BPXgEV1lizNpID1IaftxGeI/RpAx0CHQlrQHIZJItgFMiC/OMLGPCzipKVabenxxni8x4F4TTtMUk+GnYzCmJPbP5gX4qXXbPh6Is8g0uZIwvT0Ya8AlBdFs+9W1eqAkDdBL15/ev/AfKTrIb81sQdAAAAAElFTkSuQmCC" />
                            </a>
                        </div>
                    </div>
                </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td class="text-center">م</td>
                                <td><strong>إسم الصنف</strong></td>
                                <td><strong>الوحدة</strong></td>
                                <td class="text-center"><strong>الكمية</strong></td>
                                <td class="text-center"><strong>السعر</strong></td>
                                <td class="text-center"><strong>الإجمالى</strong></td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- foreach ($order->lineItems as $line) or some such thing here -->
                            @foreach($item->details as $key=>$prod)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td>{{$prod->product->title}}</td>
                                <td>{{($prod->unit->title)?$prod->unit->title:'عدد'}}</td>
                                <td class="text-center">{{$prod->qty}}</td>
                                <td class="text-center">{{$prod->price}}</td>
                                <td class="text-center">{{$prod->total}}</td>
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
