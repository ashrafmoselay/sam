<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class InvoiceDetailes extends Model {
	use Sortable;
	protected $table    = 'invoice_detailes';
	protected $fillable = [
		'invoice_id', 'product_id', 'qty', 'cost', 'total', 'unit_id', 'store_id'
	];

	public function product() {
		return $this->belongsTo('\App\Products', 'product_id', 'id');
	}
	public function invoice() {
		return $this->belongsTo('\App\PurchaseInvoice', 'invoice_id', 'id');
	}
	public function unit() {
		return $this->belongsTo('\App\Unit', 'unit_id', 'id');
	}
	public function getCostAttribute($value) {
		return round($value, 2);
	}
	public function store() {
		return $this->belongsTo('\App\Store', 'store_id', 'id');
	}
}
