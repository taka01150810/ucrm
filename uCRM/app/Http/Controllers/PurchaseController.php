<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Order::paginate(50));

        // 合計
        $orders = Order::groupBy('id')
        ->selectRaw('id, customer_name, sum(subtotal) as total, status, created_at' )
        ->paginate(50);
        
        return Inertia::render('Purchases/Index', [
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $customers = Customer::select('id', 'name', 'kana')->get();
        
        $items = Item::select('id', 'name', 'price')
        ->where('is_selling',true)
        ->get();

        return Inertia::render('Purchases/Create', [
            'items' => $items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {
        //2つのテーブルに保存するので途中でエラーなどあった場合はロールバックして戻せるようにする
        DB::beginTransaction();

        try{
            DB::commit();

            $purchase = Purchase::create([
                'customer_id' => $request->customer_id,
                'status' => $request->status,
            ]);
            
            foreach($request->items as $item){
                $purchase->items()->attach( $purchase->id, [
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity']
                ]);
            }
            
            return to_route('dashboard');

        } catch(\Exception $e){
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        // 小計
        $items = Order::where('id', $purchase->id)
        ->get();

        // 合計
        $order = Order::groupBy('id')
        ->where('id', $purchase->id)
        ->selectRaw('id, customer_name, sum(subtotal) as total, status, created_at')
        ->get();

        //dd($subtotals, $order);
        
        return Inertia::render('Purchases/Show', [
            'items' => $items,
            'order' => $order 
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        /*
        中間テーブルの数量を確認し数が入っていれば反映させたい
        Vue.js側でv-ifとv-forの組み合わせは非推奨なのでPHP側で配列をつくっておく
        */
        // 購買Idで指定
        $purchase = Purchase::find($purchase->id);
        // 全商品を取得
        $allItems = Item::select('id', 'name', 'price')
        ->get();

        $items = [];

        // 販売中の商品と中間テーブルを比較し、中間テーブルに数量があれば数量を取得、なければ0で設定
        foreach($allItems as $allItem){
            $quantity = 0; // 数量初期値 0
            foreach($purchase->items as $item){ // 中間テーブルを1件ずつチェック
                if($allItem->id === $item->id){ // 同じidがあれば
                    $quantity = $item->pivot->quantity; // 中間テーブルの数量を設定 
                }
            }
            array_push($items, [
                'id' => $allItem->id,
                'name' => $allItem->name,
                'price' => $allItem->price,
                'quantity' => $quantity 
            ]);
        }

        $order = Order::groupBy('id')
        ->where('id', $purchase->id)
        ->selectRaw('id, customer_id, customer_name, status, created_at')
        ->get();

        return Inertia::render('Purchases/Edit', [
            'items' => $items,
            'order' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        DB::beginTransaction();
        try{
            $purchase->status = $request->status;
            $purchase->save();
        
            // 中間テーブルの情報を更新するにはsync()が便利
            $items = [];
            foreach($request->items as $item){
                $items = $items + [
                    // item_id => [ 中間テーブルの列名 => 値 ]
                    $item['id'] => [ 'quantity' => $item['quantity']]
                ];
            }
            $purchase->items()->sync($items);
            DB::commit();
            return to_route('dashboard');
            
        } catch(\Exception $e){
            DB::rollback();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
