<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class PurchaseInvoice extends Model {

	use Sortable;
	protected $table    = 'purchase_invoice';
	protected $fillable = [
		'supplier_id', 'total', 'paid', 'due', 'created_at', 'discount', 'offer', 'note', 'id', 'discount_type'
	];
	public $sortable = [
		'id',
		'supplier_id',
		'total',
		'discount',
		'offer',
		'paid',
		'due',
		'created_at',
		'updated_at'];

	public function details() {
		return $this->hasMany('\App\InvoiceDetailes', 'invoice_id', 'id')->orderBy('id', 'DESC');
	}
	public function supplier() {
		return $this->belongsTo('\App\Suppliers', 'supplier_id', 'id');
	}

	public function ScopeSearch($query) {

		$from        = \Request::get("from");
		$to          = \Request::get("to");
		$supplier_id = \Request::get("supplier_id");
		$size        = (\Request::get("page_size"))?\Request::get("page_size"):\config('custom-setting.page_size');
		$search      = \Request::get("search");
		if ($supplier_id) {
			$query->where('supplier_id', $supplier_id);
		}
		if ($search) {
			$query->orwhere('id', '=', "$search")->orwhere('client_id', '=', "$search");
		}
		$query->join('invoice_detailes', 'invoice_detailes.invoice_id', '=', 'purchase_invoice.id');
		$query->groupBy('invoice_id');

		$query->select('purchase_invoice.*', \DB::raw('SUM(invoice_detailes.qty) as totalQty'));
		if (!empty($from) && !empty($to)) {
			$query->whereBetween('purchase_invoice.created_at', array($from, $to));
		} elseif (!empty($from)) {
			$query->where('purchase_invoice.created_at', '>=', $from);
		} elseif (!empty($to)) {
			$query->where('purchase_invoice.created_at', '<=', $to);
		}
		if (\Request::get('sort')) {
			$list = $query->sortable()->paginate($size);
		} else {
			$list = $query->orderBy('id', 'DESC')->paginate($size);
		}
		//dd($query->toSql());
		return $list;
	}
	public function ScopeSearch2($query) {
		$from     = \Request::get("from");
		$to       = \Request::get("to");
		$clientid = \Request::get("client");
		if (!$clientid) {return;
		}

		$client      = \App\Clients::find($clientid);
		$supplier    = \App\Suppliers::where('name', $client->name)->first();
		$supplier_id = ($supplier)?$supplier->id:0;
		$query       = $query->where('supplier_id', $supplier_id);

		if (!empty($from) && !empty($to)) {
			return $query->whereBetween('created_at', array($from, $to))->orderBy('created_at', 'DESC')->get();
		} elseif (!empty($from)) {
			return $query->where('created_at', '>=', $from)->orderBy('created_at', 'DESC')->get();
		} elseif (!empty($to)) {
			return $query->where('created_at', '<=', $to)->orderBy('created_at', 'DESC')->get();
		} else {
			return $query->orderBy('created_at', 'DESC')->get();
		}
	}
	public function getCreatedAtAttribute($value) {
		return date('Y-m-d', strtotime($value));
	}
}
