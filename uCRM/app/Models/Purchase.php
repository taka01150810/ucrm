<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Customer;
use app\Models\Item;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // 中間テーブル内の情報を取得したいため、 withPivotも使用 
    public function items()
    {
        return $this->belongsToMany(Item::class)
        ->withPivot('quantity');
    }
}
