<?php

namespace App\Http\Controllers;

use Artisan;
use DB;
use Illuminate\Http\Request;

class HomeController extends BaseController {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}
	public function GetCheque()
	{
		try {
			DB::beginTransaction();
			$allcheq = \App\Cheq::where('is_paid',0)
				->where('auto',1)
				->where('date','<=',date('Y-m-d'))
				->get();
			//dd($allcheq );
			foreach ($allcheq as $cheq) {
				$cheq->update(['is_paid' => 1]);
				$inputs["bank_id"] = $cheq->bank_id;
				$inputs["note"] = "خصم قيمة الشيك رقم " . $cheq->cheq_num . " لمورد " . $cheq->supplier->name;
				$inputs["op_date"] = date('Y-m-d');
				$inputs["type"] = "1";
				$inputs["total"] = $cheq->bank->balance;
				$inputs["value"] = $cheq->value;
				$inputs["due"] = $cheq->bank->balance - $cheq->value;
				\App\Transaction::create($inputs);
				$cheq->bank->balance -= $cheq->value;
				$cheq->bank->save();
				$supplier = \App\Suppliers::find($cheq->supplier_id);
				$payment["supplier_id"] = $cheq->supplier_id;
				$payment["esal_num"] = " رقم الشيك " . $cheq->cheq_num;
				$payment["created_at"] = date('Y-m-d');
				$payment["total"] = $supplier->due;
				$payment["paid"] = $cheq->value;
				$payment["due"] = $supplier->due - $cheq->value;
                $payment["payment_type"] = 2;
				\App\SupplierPayments::create($payment);
				$supplier->paid += $cheq->value;
				$supplier->due -= $cheq->value;
				$supplier->save();
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			//dd($e->getMessage());
		}
	}
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function daily() {
		$date = $this->ArabicDate();
		return view('daily', compact('date'));
	}
	public function report() {
		$date = $this->ArabicDate();
		return view('report', compact('date'));
	}
	public function index() {
		$this->GetCheque();
		return view('home');
	}
	public function ArabicDate() {
		$months    = array("Jan" => "يناير", "Feb" => "فبراير", "Mar" => "مارس", "Apr" => "أبريل", "May" => "مايو", "Jun" => "يونيو", "Jul" => "يوليو", "Aug" => "أغسطس", "Sep" => "سبتمبر", "Oct" => "أكتوبر", "Nov" => "نوفمبر", "Dec" => "ديسمبر");
		$your_date = date('y-m-d');// The Current Date
		$en_month  = date("M", strtotime($your_date));
		foreach ($months as $en => $ar) {
			if ($en == $en_month) {$ar_month = $ar;}
		}

		$find          = array("Sat", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri");
		$replace       = array("السبت", "الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة");
		$ar_day_format = date('D');// The Current Day
		$ar_day        = str_replace($find, $replace, $ar_day_format);

		header('Content-Type: text/html; charset=utf-8');
		$standard               = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
		$eastern_arabic_symbols = array("٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩");
		$current_date           = $ar_day.' '.date('d').' / '.$ar_month.' / '.date('Y');
		$arabic_date            = str_replace($standard, $eastern_arabic_symbols, $current_date);

		return $arabic_date;
	}
	public function closeYear(Request $request) {
		try {
			DB::beginTransaction();
			//DB::statement("UPDATE products SET quantity = quantity - sale_count,sale_count = 0;");
			DB::statement("UPDATE products_store SET qty = qty - sale_count,sale_count = 0;");
			DB::statement("UPDATE suppliers SET total = due,paid=0;");
			DB::statement("UPDATE clients SET total = due,paid=0;");
			DB::table('order_detailes')->truncate();
			DB::table('invoice_detailes')->truncate();
			DB::table('purchase_invoice')->truncate();
			DB::table('orders')->truncate();
			DB::table('client_payments')->truncate();
			DB::table('supplier_payments')->truncate();
			DB::table('installment')->truncate();
			DB::table('masrofat')->truncate();
			DB::table('treasury_movement')->truncate();
			DB::table('orders_return_details')->truncate();
			DB::table('orders_returns')->truncate();
			DB::table('products_transit')->truncate();
			DB::table('return_details')->truncate();
			DB::table('returns')->truncate();
			DB::table('cheq')->truncate();
			DB::table('transactions')->truncate();
			//DB::statement('SET FOREIGN_KEY_CHECKS=1;');
			$request->session()->flash('alert-success', 'تمت العملية بنجاح');
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('alert-danger', trans('app.Some Error was ocuured during opertion!').$e->getMessage());
		}
		return redirect('home');

	}

	public function backup(Request $request) {
		Artisan::call('backup:run');
		$request->session()->flash('alert-success', trans("app.Successfuly Opertion"));
		return redirect('home');
	}
	public function printPdf(Request $request, $model) {
		//dd($request->all());
		set_time_limit(0);
		$condition = [];
		$whereRaw  = false;
		foreach ($request->all() as $key => $value) {
			if ($value) {
				if ($key == 'from') {
					$condition[] = ['created_at', '>=', $value];
				} elseif ($key == 'to') {
					$condition[] = ['created_at', '<=', $value];
				} elseif ($key == "sharek_id" && $value == "g") {
					$condition[] = ['type', '=', 1];
				} elseif ($key == "category_id" && $value == "observe") {
					//dd("here");
					$whereRaw = true;
					//$condition[] = whereRaw['quantity-sale_count','=','observe'];
					//$condition[] = 'quantity-sale_count <='.$observe." OR quantity-sale_count = observe";
				} else {

					$condition[] = [$key, '=', $value];
				}
			}
		}

		//dd($condition);
		// create new PDF document
		$pdf = new MyTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetAuthor('Ashraf Hassan');
		$pdf->SetTitle($model);

		// set margins
		//$pdf->SetMargins(5, 5, 5);
		//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		//$pdf->SetFooterMargin(15);
		//$pdf->setPrintHeader(false);

		// set default header data
		$pdf->SetHeaderData('', '', \Config::get('custom-setting.SiteName'), '');
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, \Config::get('custom-setting.SiteName'), PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array('aealarabiya', '', 18));
		$pdf->setFooterFont(Array('aealarabiya', '', 12));
		//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 20);

		// set image scale factor
		//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$pdf->setRTL(true);
		// set some language dependent data:
		$lg                    = Array();
		$lg['a_meta_charset']  = 'UTF-8';
		$lg['a_meta_dir']      = 'rtl';
		$lg['a_meta_language'] = 'ara';
		$lg['w_page']          = '';
		$pdf->setLanguageArray($lg);
		$pdf->SetFont('aealarabiya', '', 12);
		//$pdf->SetFont('dejavusans', '', 12);
		//$pdf->SetFont('aefurat', '', 12);
		$pdf->AddPage();
		$modelName = "\App\\$model";
		if ($request->get('invoiceID')) {
			$id      = $request->get('invoiceID');
			$invoice = $modelName::with('details')->findOrFail($id);
			$html    = view("orders.pdf", compact('invoice'));
		} else {

			if ($whereRaw) {
				$observe = \Config::get('custom-setting.observe');

				$list = $modelName::where($condition)->whereRaw('quantity-sale_count <='.$observe." OR quantity-sale_count = observe")->orderBy('id', 'desc')->get();
			} else {
				$list = $modelName::where($condition)->orderBy('id', 'desc')->get();
			}

			$html = view("prints.$model", compact('list'));
		}
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output($model.'.pdf', 'I');
	}

	public function productlistCode() {

		try {
			$barcodeobj = new \TCPDFBarcode('product', 'C128');
			// output the barcode as HTML object
			echo $barcodeobj->getBarcodeHTML(2, 30, 'black');
			die;
			$list = Products::get();
			$html = view('products.barcode', compact('list'));
			return $html;

			$pdf = PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->save('barcode.pdf');
			return $pdf->stream('barcode.pdf');
		} catch (\Exception $e) {
			//var_dump($e->getMessage());
			die;
			$request->session()->flash('alert-danger', 'حدث خطأ غير متوقع من فضلك تأكد من الباركود الخاصة باﻷصناف لابد ان تحتوى على أرقام او حروف انجليزية فقط');
			return redirect('home');
		}
	}
	public function restore(Request $request) {
		if ($request->isMethod('post')) {
			$file = $request->file('file');
			$file->storeAs('', 'database.sqlite');
			$request->session()->flash('alert-success', 'تم إسترجاع نسخة البيانات بنجاح');
			return redirect('home');
		}
		return view('restore');
	}

	public function clientsupplier(Request $request) {
		if ($request->ajax()) {
			$orders           = \App\Orders::search2();
			$purchase         = \App\PurchaseInvoice::search2();
			$clientPayments   = \App\ClientPayments::search2();
			$supplierPayments = \App\SupplierPayments::search2();
			$cltId            = $request->get('client');
			$returns          = \App\OrderReturnDetails::whereHas('invoice', function ($query) use ($cltId) {
					$query->where('orders_returns.client_id', $cltId);
				})->get();
			$client   = \App\Clients::find($cltId);
			$supplier = \App\Suppliers::where('name', $client->name)->first();
			$supId    = ($supplier)?$supplier->id:0;
			$preturns = \App\ReturnDetails::whereHas('invoice', function ($query) use ($supId) {
					$query->where('returns.supplier_id', $supId);
				})->get();
			return view('client_supplier_list', compact('orders', 'purchase', 'clientPayments', 'supplierPayments', 'returns', 'preturns', 'client', 'supplier'));
		} else {
			return view('client_supplier');
		}
	}
}
class MyTCPDF extends \TCPDF {
    public function Footer() {
        $this->SetX($this->original_lMargin);
        $this->SetY(-15);
        $footer_data = \Config::get('custom-setting.Address');
        $this->Cell(0, 10, $footer_data, 'T', false, 'R');
        $this->Cell(0, 10, $this->getAliasNumPage().'/'.$this->getAliasNbPages(), 'T', 0, 'L');
    }

}