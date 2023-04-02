<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Alert extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];
    protected $fillable = [
        'id',
        'product_id',
        'name',
    ];

    public function product()
    {
        $this->belongsTo(Product::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }
}
