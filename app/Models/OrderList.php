<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderList extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'product_id',
        'order_id',
        'amount',
        'netto',
        'brutto',
        'vat'
        ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

