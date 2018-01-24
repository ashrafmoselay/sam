<table class="table table-hover display table-bordered">
    <thead>
    <tr>
        <th>م</th>
        <th>اسم البنك</th>
        <th>رقم الشيك</th>
        <th>المحول اليه</th>
        <th>المبلغ</th>
        <th>تاريخ الاستحقاق</th>
        <th>خصم اتوماتيك</th>
        <th>الحالة</th>
        <th>{{ trans('app.action') }}</th>
    </tr>
    </thead>
    <tbody>
        @foreach($list as $key=>$item)
            <tr>
                <td> {{ $item->id }} </td>
                <td> {{ $item->bank->name }} </td>
                <td> {{ $item->cheq_num }} </td>
                <td> {{ $item->supplier->name }} </td>
                <td>{{$item->value}}</td>
                <td>{{$item->date}}</td>
                <td><img src="{{asset('icon')}}/{{ $item->auto }}.png"></td>
                <td>
                    <a href="#" class="changeStatus" rel="{{$item->id}}" status="{{ $item->is_paid }}">
                        <img src="{{asset('icon')}}/{{ $item->is_paid }}.png">
                    </a>
                </td>
                <td>
                    <a class="btn btn-primary" href="cheq/{{ $item->id }}/edit" role="button">{{ trans('app.Edit') }}</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="text-center">
{{$list->render()}}
</div>