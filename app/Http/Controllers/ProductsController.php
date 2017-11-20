<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;
use App\ProductStoreUnit;
use QrCode;
use PDF;
use DB;
class ProductsController extends BaseController
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($this->decToFraction(206.333333333333 ));
        if($request->get('sort')){
            $list = Products::with('category')->sortable()->Paginate(\config('custom-setting.page_size'));
        }else{
            $list = Products::with('category')->sortable()->orderBy('id','DESC')->Paginate(\config('custom-setting.page_size'));
        }
       
        $query = DB::table('products_store')
        ->select(DB::raw('sum((qty-sale_count)*cost)  as TotalCost'))->first();
        $totalRemainInStock = isset($query->TotalCost)?$query->TotalCost:0;
        //$totalQty = isset($query->TotalQty)?$query->TotalQty:0;
        $title = "Products Index";
        /*\Excel::create('New file', function($excel)use($list) {

            $excel->sheet('New sheet', function($sheet)use($list) {
                $totalRemainInStock =0;
                $sheet->loadView('products._list',compact('list','totalRemainInStock'));

            });

        })->download('xls');*/
        return view('products.index',compact('title','list','totalRemainInStock','totalQty'));
    }

    public function productlistCode(){
        try{
            $list = Products::get();
            $html =  view('products.barcode',compact('list'));
            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetAuthor('Ashraf Hassan');
            $pdf->SetTitle('باركود اﻷصناف');
            $pdf->SetFooterMargin(15);
            $pdf->setPrintHeader(false);
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetAutoPageBreak(TRUE, 20);
            $pdf->setRTL(true);
            $lg = Array();
            $lg['a_meta_charset'] = 'UTF-8';
            $lg['a_meta_dir'] = 'rtl';
            $lg['a_meta_language'] = 'ara';
            $lg['w_page'] = '';
            $pdf->setLanguageArray($lg);
            $pdf->SetFont('aealarabiya', '', 8);
            $pdf->AddPage();
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Output('باركود اﻷصناف.pdf', 'I');

       }catch(\Exception $e){
             //var_dump($e->getMessage());die;
            $request->session()->flash('alert-danger', 'حدث خطأ غير متوقع من فضلك تأكد من الباركود الخاصة باﻷصناف لابد ان تحتوى على أرقام او حروف انجليزية فقط');
            return redirect('home');
        }
    }
    
    public function barcode($id)
    {
        $product = Products::find($id);
        $codes = explode('|',$product->code);
        return view('products.product_code',compact('codes'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Products | Create';
        $item = new Products;
        return view('products.create',compact('title','item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $inputs = $request->all();
            //dd($inputs);
            $inputs['last_avg_cost'] = '';
            if(empty($inputs['code'])){
                $inputs['code'] = str_random(6).rand(1,9);
            }
            //$inputs['quantity'] = $inputs['qty'][0];
            $request->replace($inputs);
            $this->validate($request,[
                    'code'=>[
                        'unique:products,code'
                        ]
                ],['unique'=>'الباركود موجود بالفعل']);

            $id = Products::create($inputs)->id;
            $storecostunit = [];
            if(isset($inputs['unit'])){
                foreach($inputs['unit'] as $k=>$unit){
                    $data['unit_id'] = $unit;
                    $data['pieces_num'] = $inputs['pices_num'][$k];
                    $data['product_id'] = $id;
                    $data['cost_price'] = $inputs['unit_cost'][$k];
                    $data['sale_price'] = $inputs['unit_price'][$k];
                    $data['price2'] = $inputs['price2'][$k];
                    $data['price3'] = $inputs['price3'][$k];
                    $data['default_sale'] = isset($inputs['default_sale'][$k])?1:0;
                    $data['default_purchase'] = isset($inputs['default_purchase'][$k])?1:0;
                    $storecostunit[$unit] = $data['cost_price'];
                    ProductStoreUnit::create($data);
                }
            }
            for ($i=0; $i < count($inputs['store']); $i++) {
                //if($i==0) continue; 
                $store['store_id'] = $inputs['store'][$i];
                $store['qty'] = $inputs['qty'][$i];
                $store['cost'] = isset($storecostunit[$inputs['store_unit'][$i]])?$storecostunit[$inputs['store_unit'][$i]]:$inputs['cost'];
                $store['unit_id'] = $inputs['store_unit'][$i];
                $store['product_id'] = $id;
                \App\ProductsStore::create($store);
            }

            $request->session()->flash('alert-success', 'تمت العملية بنجاح');
            DB::commit();
        }catch(Exception $e){
             DB::rollback();
            //dd($e->getMessage());
             //var_dump($e->getMessage());die;
            $request->session()->flash('alert-danger', trans('app.Some Error was ocuured during adding! ').$e->getMessage());
             return redirect('products/create');
        }
        return redirect('products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Products::with('admin/Products')->findOrFail($id);
        $title = 'Products | '.$category->name; 
        return view('admin.Products.show',compact('Products','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $item = Products::findOrFail($id);
         $title = 'Products | '.$item->title; 
         return view('products.edit',compact('item','title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $Products = Products::find($id);
            $inputs = $request->all();
            //dd($inputs);
            if(empty($inputs['code'])){
                $inputs['code'] = str_random(6).rand(1,9);
            }
            $request->replace($inputs);
            $this->validate($request,[
                    'code'=>[
                        'unique:products,code,'.$id
                        ]
                ],['unique'=>'الباركود موجود بالفعل']);
            $Products->update($inputs);
            ProductStoreUnit::where('product_id',$id)->delete();
            $storecostunit = [];
            if(isset($inputs['unit'])){
                foreach($inputs['unit'] as $k=>$unit){
                    $data['unit_id'] = $unit;
                    $data['pieces_num'] = $inputs['pices_num'][$k];
                    $data['product_id'] = $id;
                    $data['cost_price'] = $inputs['unit_cost'][$k];
                    $data['sale_price'] = $inputs['unit_price'][$k];
                    $data['price2'] = $inputs['price2'][$k];
                    $data['price3'] = $inputs['price3'][$k];
                    $data['default_sale'] = isset($inputs['default_sale'][$k])?1:0;
                    $data['default_purchase'] = isset($inputs['default_purchase'][$k])?1:0;
                    $storecostunit[$unit] = $data['cost_price'];
                    ProductStoreUnit::create($data);
                }
            }
            for ($i=0; $i < count($inputs['store']); $i++) {
                //if($i==0) continue;
                $store['store_id'] = $inputs['store'][$i];
                $store['qty'] = $inputs['qty'][$i];
                $store['unit_id'] = $inputs['store_unit'][$i];
                $store['product_id'] = $id;
                $store['cost'] = isset($storecostunit[$inputs['store_unit'][$i]])?$storecostunit[$inputs['store_unit'][$i]]:$inputs['cost'];
                $storeRaw =  \App\ProductsStore::where('product_id',$id)->where('store_id',$store['store_id'])->first();
                if(count($storeRaw)){
                    $store['qty'] = $inputs['qty'][$i] + $storeRaw->sale_count;
                    $storeRaw->update($store);
                }else{
                    \App\ProductsStore::create($store);
                }
            }

            $request->session()->flash('alert-success', 'تمت العملية بنجاح');
            DB::commit();
        }catch(Exception $e){
             DB::rollback();
            //dd($e->getMessage());
             //var_dump($e->getMessage());die;
            $request->session()->flash('alert-danger', trans('app.Some Error was ocuured during adding! ').$e->getMessage());
             return redirect('products/create');
        }
            return redirect('products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       Products::find($id)->delete();
       return redirect('products');
    }
    public function search(Request $request,$term=''){
        $cat = $request->get('category_id');
        $cat2 = $request->get('category2_id');
        $list =  Products::search($term,$cat);
        $totalRemainInStock = 0;
        $query = DB::table('products')
                ->join('products_store','product_id','products.id')
                ->select(DB::raw('sum((qty-sale_count)*products_store.cost)  as TotalCost'));
        if($cat){
            if($cat == "observe"){
                $query = $query->whereRaw('qty-sale_count <= observe');
            }else{
                $query = $query->where('category_id',$cat);
            }
        }
        if($cat2){
            $query = $query->where('category2_id',$cat2);
        }
        if($term){
            $query = $query->where('products.title','like',"%$term%")->orWhere('model','like',"%$term%");

        }
        $query = $query->first();    
        $totalRemainInStock = isset($query->TotalCost)?$query->TotalCost:0;
        $totalQty = 0;
        if($request->ajax()){
        return \View::make('products._list',compact('list','totalRemainInStock','totalQty'));
        }else{
            $title = 'Products | Create';
            return view('products.index',compact('title','list','totalRemainInStock','totalQty'));
        }
    }
    public function qtyDetailes($id)
    {
        $list = \App\InvoiceDetailes::where('product_id',$id)->Paginate(\config('custom-setting.page_size'));
        $title = 'Products | Create';
        $totalqty = Products::find($id)->quantity;
        return view('products.qtyDetailes',compact('title','list','totalqty'));
    }
    public function movement($id)
    {
        $product = Products::find($id);
        $purchaseList = \App\InvoiceDetailes::where('product_id',$id)->get();
        $salesList = \App\OrderDetails::where('product_id',$id)->get();
        $returns = \App\ReturnDetails::where('product_id',$id)->get();
        $totalqty = Products::find($id)->quantity;
        return view('products.movement',compact('product','totalqty','salesList','purchaseList','returns'));
    }

    public function salesDetailes($id)
    {
        $list = \App\OrderDetails::where('product_id',$id)->Paginate(\config('custom-setting.page_size'));
        $title = 'Products | Create';
        return view('products.salesDetailes',compact('title','list'));
    }
    public function checkCode(Request $request){
        $barcode = $request->get('barcode');
        $find = Products::where('code',$barcode)->first();
        return $find;
    }

    public function convertNumberToText($number) {
        $hyphen      = '-';
        $conjunction = ' و ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'واحد',
            2                   => 'أثنين',
            3                   => 'ثلاثة',
            4                   => 'أربعة',
            5                   => 'خمسة',
            6                   => 'ستة',
            7                   => 'سبعة',
            8                   => 'ثمانية',
            9                   => 'تسعة',
            10                  => 'عشرة',
            11                  => 'إحدى عشر',
            12                  => 'إثنى عشر',
            13                  => 'ثلاثة عشر',
            14                  => 'أربعة عشر',
            15                  => 'خمسة عشر',
            16                  => 'ستة عشر',
            17                  => 'سبعة عشر',
            18                  => 'ثمانية عشر',
            19                  => 'تسعة عشر',
            20                  => 'عشرون',
            30                  => 'ثلاثون',
            40                  => 'أربعون',
            50                  => 'خمسون',
            60                  => 'ستون',
            70                  => 'سبعون',
            80                  => 'ثمنون',
            90                  => 'تسعون',
            100                 => 'مائة',
            1000                => 'ألف',
            1000000             => 'مليون',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convertNumberToText(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convertNumberToText($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convertNumberToText($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convertNumberToText($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
}
