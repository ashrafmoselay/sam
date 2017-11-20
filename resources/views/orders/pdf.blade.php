@include('prints/print')

    كود العميل : {{$invoice->client->id}}{{str_repeat('&nbsp;', 15)}}اسم العميل : {{$invoice->client->name}}{{str_repeat('&nbsp;', 15)}}نوع الفاتورة : {{$invoice->payment_type}}{{str_repeat('&nbsp;', 15)}}التاريخ : {{$invoice->created_at}} <br> <br>
    <table class="table">
        <thead>
            <tr>
                <td>الرقم</td>
                <td><strong>إسم الصنف</strong></td>
                <td class="text-center"><strong>الكمية</strong></td>
                <td class="text-center"><strong>السعر</strong></td>
                <td class="text-center"><strong>الإجمالى</strong></td>
            </tr>
        </thead>
        <tbody>
            <!-- foreach ($order->lineItems as $line) or some such thing here -->
            @foreach($invoice->details as $key=>$prod)
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td>{{$prod->product->title}}</td>
                <td class="text-center">{{$prod->qty}}</td>
                <td class="text-center">{{$prod->price}}</td>
                <td class="text-center">{{$prod->total}}</td>
            </tr>
            @endforeach
            
        </tbody>
        <tfoot> 
            <tr><td style="border:none;" colspan="5"></td></tr> 
            <tr>
                <td style="border:none;" colspan="3"></td>
                <td><strong>إجمالى الفاتورة  </strong></td>
                <td>{{$invoice->total}}</td>
            </tr>
            <tr>
                <td style="border:none;" colspan="3"></td>
                <td><strong>المبلغ المدفوع  </strong></td>
                <td>{{$invoice->paid}}</td>
            </tr>
            <tr>
                <td style="border:none;" colspan="3"></td>
                <td><strong>المبلغ المتبقى  </strong></td>
                <td>{{$invoice->due}}</td>
            </tr>
        </tfoot>
    </table>