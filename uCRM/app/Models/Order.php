<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\Subtotal;

class Order extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new Subtotal);
    }

    // どの分析においても、何年何月日から 何年何月日 までという情報は必要
    public function scopeBetweenDate($query, $startDate = null, $endDate = null)
    {
        if(is_null($startDate) && is_null($endDate)){
            return $query;
        }
        
        if(!is_null($startDate) && is_null($endDate)){
            return $query->where('created_at', ">=", $startDate);
        }
        
        if(is_null($startDate) && !is_null($endDate)){
            return $query->where('created_at', '<=', $endDate);
        }
        
        if(!is_null($startDate) && !is_null($endDate)){
            return $query->where('created_at', ">=", $startDate)
            ->where('created_at', '<=', $endDate);
        }
    }
}
