<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index()
    {
        // 期間指定
        $startDate = '2021-08-01';
        $endDate = '2022-08-31';

        // 1. 購買ID毎にまとめる
        $subQuery = Order::betweenDate($startDate, $endDate)
        ->groupBy('id')
        ->selectRaw('id, customer_id, customer_name, SUM(subtotal) as totalPerPurchase');
        
        // 2. 会員毎にまとめて購入金額順にソートする
        $subQuery = DB::table($subQuery)
        ->groupBy('customer_id')
        ->selectRaw('customer_id, customer_name, sum(totalPerPurchase) as total')
        ->orderBy('total', 'desc');

        // statementで変数を設定できる
        // set @変数名 = 値 (mysqlの書き方)
        // 3. 購入順に連番を振る
        DB::statement('set @row_num = 0;');
        
        $subQuery = DB::table($subQuery)
        ->selectRaw('
        @row_num:= @row_num+1 as row_num,
        customer_id,
        customer_name,
        total');

        // 4. 全体の件数を数え、1/10の値や合計金額を取得
        $count = DB::table($subQuery)->count();
        $total = DB::table($subQuery)->selectRaw('sum(total) as total')->get();
        $total = $total[0]->total; // 構成比用
        $decile = ceil($count / 10); // 10分の1の件数を変数に入れる

        $bindValues = [];
        $tempValue = 0;

        for($i = 1; $i <= 10; $i++){
            array_push($bindValues, 1 + $tempValue);
            $tempValue += $decile;
            array_push($bindValues, 1 + $tempValue);
        }
        
        // 5 10分割しグループ毎に数字を振る
        DB::statement('set @row_num = 0;');
        
        // SelectRaw第二引数にバインドしたい数値(配列)をいれる
        $subQuery = DB::table($subQuery)
        ->selectRaw("
        row_num,
        customer_id,
        customer_name,
        total,
        case
        when ? <= row_num and row_num < ? then 1
        when ? <= row_num and row_num < ? then 2
        when ? <= row_num and row_num < ? then 3
        when ? <= row_num and row_num < ? then 4
        when ? <= row_num and row_num < ? then 5
        when ? <= row_num and row_num < ? then 6
        when ? <= row_num and row_num < ? then 7
        when ? <= row_num and row_num < ? then 8
        when ? <= row_num and row_num < ? then 9
        when ? <= row_num and row_num < ? then 10
        end as decile
        ", $bindValues);

        // round, avg はmysqlの関数
        // 6. グループ毎の合計・平均
        $subQuery = DB::table($subQuery)
        ->groupBy('decile')
        ->selectRaw('
        decile,
        round(avg(total)) as average,
        sum(total) as totalPerGroup
        ');

        // 構成比を出すために変数を使う
        // 7 構成比
        DB::statement("set @total = ${total} ;");
        $data = DB::table($subQuery)
        ->selectRaw('
        decile,
        average,
        totalPerGroup,
        round(100 * totalPerGroup / @total, 1) as totalRatio
        ')
        ->get();

        // dd($data);

        // 1. 購買ID毎にまとめる
        $subQuery = Order::betweenDate($startDate, $endDate)
        ->groupBy('id')
        ->selectRaw('
            id,
            customer_id,
            customer_name,
            SUM(subtotal) as totalPerPurchase,
            created_at
        ');

        // datediffで日付の差分, maxで日付の最新日
        // 2. 会員毎にまとめて最終購入日、回数、合計金額を取得
        $subQuery = DB::table($subQuery)
        ->groupBy('customer_id')
        ->selectRaw('
            customer_id, 
            customer_name,
            max(created_at) as recentDate, 
            datediff(now(), 
            max(created_at)) as recency,
            count(customer_id) as frequency,
            sum(totalPerPurchase) as monetary
        ');

        // 4. 会員毎のRFMランクを計算
        $subQuery = DB::table($subQuery)
        ->selectRaw('
        customer_id,
        customer_name,
        recentDate,
        recency,
        frequency,
        monetary,
        case
            when recency < 14 then 5
            when recency < 28 then 4
            when recency < 60 then 3
            when recency < 90 then 2
            else 1 end as r,
        case
            when 7 <= frequency then 5
            when 5 <= frequency then 4
            when 3 <= frequency then 3
            when 2 <= frequency then 2
            else 1 end as f,
        case
            when 300000 <= monetary then 5
            when 200000 <= monetary then 4
            when 100000 <= monetary then 3
            when 30000 <= monetary then 2
            else 1 end as m
        ');

        // 5.ランク毎の数を計算する
        $total = DB::table($subQuery)->count();

        $rCount = DB::table($subQuery)
        ->groupBy('r')
        ->selectRaw('r, count(r)')
        ->orderBy('r', 'desc')
        ->get();

        $fCount = DB::table($subQuery)
        ->groupBy('f')
        ->selectRaw('f, count(f)')
        ->orderBy('f', 'desc')
        ->get();

        $mCount = DB::table($subQuery)
        ->groupBy('m')
        ->selectRaw('m, count(m)')
        ->orderBy('m', 'desc')
        ->get();

        dd($subQuery,  $total, $rCount, $fCount, $mCount);

        return Inertia::render('Analysis');
    } 
}
