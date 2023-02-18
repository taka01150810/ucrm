<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\Subtotal;
use Carbon\Carbon;

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
        
        /*
        日付を指定すると 2022-08-31 00:00:00 になり
        2022-08-31 14:00:00 などが含まれなくなるのでクエリを修正 
        */
        if(is_null($startDate) && !is_null($endDate)){
            $endDate1 = Carbon::parse($endDate)->addDays(1);
            return $query->where('created_at', '<=', $endDate1);
        }
        
        if(!is_null($startDate) && !is_null($endDate)){
            $endDate1 = Carbon::parse($endDate)->addDays(1);
            return $query->where('created_at', ">=", $startDate)
            ->where('created_at', '<=', $endDate1);
        }
    }
}
