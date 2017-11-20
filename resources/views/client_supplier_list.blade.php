<div class="row">
    <div class="page-header">
      <h3>الرصيد</h3>
    </div>
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <td colspan="3">الرصيد</td>
                </tr>
                <tr>
                    <td>رصيد العميل</td>
                    <td>رصيد المورد</td>
                    <td>الرصيد النهائى</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php 
                        $clientdue = $client->due;
                        $supplierdue = ($supplier)?$supplier->due:0
                    ?>
                    <td class="success">{{$clientdue}}</td>
                    <td class="danger">{{$supplierdue}}</td>
                    <td class="warning">{{$supplierdue - $clientdue }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="page-header">
      <h3>فواتير المبيعات والمشتريات</h3>
    </div>
    <div class="col-md-6 scroll">   
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <td colspan="8">المبيعات</td>
                </tr>
                <tr>
                    <th>م</th>
                    <th>الفاتورة</th> 
                    <th>الدفع</th>
                    <th>الإجمالى</th>
                    <th>الخصم</th>
                    <th>المدفوع</th>
                    <th>المتبقى</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $i=>$item)
                    <tr>

                        <td> {{ $i+1 }} </td>
                        <td> {{ $item->id }} </td>
                        <td> {{ $item->payment_type }} </td>
                        <td> {{ $item->total }} </td>
                        <td> {{ $item->discount }} </td>
                        <td> {{ $item->paid }} </td>
                        <td> {{ $item->due }} </td>
                        <td> {{ $item->created_at }} </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="info">
                    <td colspan="3">المجموع</td>
                    <td>{{$orders->sum('total')}}</td>
                    <td>{{$orders->sum('discount')}}</td>
                    <td>{{$orders->sum('paid')}}</td>
                    <td>{{$orders->sum('due')}}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-6 scroll"> 
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <td colspan="8">المشتريات</td>
                </tr>
                <tr>
                    <th>م</th>
                    <th>الفاتورة</th>
                    <th>{{trans('app.Total') }}</th>
                    <th>الخصم</th>
                    <th>العرض</th>

                    <th>المدفوع</th>
                    <th>المتبقى</th>
                    <th>'التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase as $i=>$item)
                    <tr>
                        <td> {{ $i+1 }} </td>
                        <td> {{ $item->id }} </td>
                        <td> {{ $item->total }} </td>
                        <td> {{ $item->discount }} </td>
                        <td> {{ $item->offer }} </td>
                        <td> {{ $item->paid }} </td>
                        <td> {{ $item->due }} </td> 
                        <td> {{ $item->created_at }} </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
            <?php 
                $total = $purchase->sum('total');
                $discount = $purchase->sum('discount');
                $offer = $purchase->sum('offer');
                $final = $total - $discount - $offer;
            ?>
                <tr class="danger">
                    <td colspan="2">المجموع</td>
                    <td>{{$total}}</td>
                    <td>{{$discount}}</td>
                    <td>{{$offer}}</td>
                    <td>{{$purchase->sum('paid')}}</td>
                    <td colspan="3">{{$purchase->sum('due')}}</td>
                </tr>
                <tr class="warning">
                    <td colspan="2">إجمالى بعد الخصم والعرض</td>
                    <td style="text-align: center;" colspan="3">{{$final}}</td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="page-header">
      <h3>المدفوعات والمقبوضات</h3>
    </div>
    <div class="col-md-6 scroll">
        <table class="table table-hover table-bordered">
            <thead>
                <tr class="active">
                    <th>{{ trans('app.ID') }}</th>
                    <th>{{ trans('app.Client Name') }}</th>
                    <th>{{ trans('app.Total') }}</th>
                    <th>{{ trans('app.Paid') }}</th>
                    <th>{{ trans('app.Due') }}</th>
                    <th>{{ trans('app.Created') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($clientPayments as $key=>$clt)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$clt->client->name}}</td>
                <td>{{$clt->total}}</td>
                <td>{{$clt->paid}}</td>
                <td>{{$clt->due}}</td>
                <td>{{date('Y-m-d', strtotime($clt->created_at))}}</td>
            </tr>
            @endforeach
            <tfoot>
                <tr class="danger">
                    <td colspan="2">{{trans('app.Total')}}</td>
                    <td></td>
                    <td>{{$clientPayments->sum('paid')}}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            </tbody> 
        </table>
    </div>
    <div class="col-md-6 scroll"> 
        <table class="table table-hover table-bordered">
            <thead>
                <tr class="active">
                    <th>{{ trans('app.ID') }}</th>
                    <th>{{ trans('app.Supplier Name') }}</th>
                    <th>{{ trans('app.esal_num') }}</th>
                    <th>{{ trans('app.Total') }}</th>
                    <th>{{ trans('app.Paid') }}</th>
                    <th>{{ trans('app.Due') }}</th>
                    <th>{{ trans('app.Created') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($supplierPayments as $key=>$clt)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$clt->supplier->name}}</td>
                <td>{{$clt->esal_num}}</td>
                <td>{{$clt->total}}</td>
                <td>{{$clt->paid}}</td>
                <td>{{$clt->due}}</td>
                <td>{{date('Y-m-d', strtotime($clt->created_at))}}</td>
            </tr>
            @endforeach
            <tfoot>
                <tr class="danger">
                    <td colspan="3">{{trans('app.Total')}}</td>
                    <td></td>
                    <td>{{$supplierPayments->sum('paid')}}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            </tbody>
        </table>
    </div>
    <div class="page-header">
      <h3>المرتجعات</h3>
    </div>
    <div class="col-md-6 scroll">
        <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <td colspan="7">مرتجعات المبيعات</td>
                </tr>
                <tr class="active">
                    <th>{{ trans('app.ID') }}</th>
                    <th>التاريخ</th>
                    <th>الصنف</th>
                    <th>الدفع</th>
                    <th>{{ trans('app.Cost Price') }}</th>
                    <th>{{ trans('app.Qantity') }}</th>
                    <th>{{ trans('app.Total') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($returns as $key=>$prod)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$prod->invoice->created_at}}</td>
                <td>{{$prod->product->title}}</td>
                <td>{{($prod->invoice->is_subtract)?'أجل':'كاش'}}</td>
                <td>{{$prod->cost}}</td>
                <td>{{$prod->qty}}</td>
                <td>{{$prod->total}}</td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr class="danger">
                    <td colspan="5">{{trans('app.Total')}}</td>
                    <td>{{ $returns->sum('qty') }}</td>
                    <td>{{$returns->sum('total')}}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-6 scroll">
        <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <td colspan="7">مرتجعات المشتريات</td>
                </tr>
                <tr class="active">
                    <th>{{ trans('app.ID') }}</th>
                    <th>التاريخ</th>
                    <th> الصنف</th>
                    <th>الدفع</th>
                    <th>{{ trans('app.Cost Price') }}</th>
                    <th>{{ trans('app.Qantity') }}</th>
                    <th>{{ trans('app.Total') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($preturns as $key=>$prod)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$prod->invoice->created_at}}</td>
                <td>{{$prod->product->title}}</td>
                <td>{{($prod->invoice->is_subtract)?'أجل':'كاش'}}</td>
                <td>{{$prod->cost}}</td>
                <td>{{$prod->qty}}</td>
                <td>{{$prod->total}}</td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr class="danger">
                    <td colspan="5">{{trans('app.Total')}}</td>
                    <td>{{ $preturns->sum('qty') }}</td>
                    <td>{{$preturns->sum('total')}}</td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>