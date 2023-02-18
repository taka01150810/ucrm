<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class AnalysisService
{
    public static function perDay($subQuery)
    {
        $query = $subQuery
        ->where('status', true)
        ->groupBy('id')
        ->selectRaw(
            'SUM(subtotal) AS totalPerPurchase,
            DATE_FORMAT(created_at, "%Y%m%d") AS date'
        )
        ->groupBy('date');
        
        $data = DB::table($query)
        ->groupBy('date')
        ->selectRaw('date, sum(totalPerPurchase) as total')
        ->get();

        // $data・・日別集計 コレクション型
        $labels = $data->pluck('date');
        $totals = $data->pluck('total');

        return [
            $data,
            $labels,
            $totals
        ];
    }
}