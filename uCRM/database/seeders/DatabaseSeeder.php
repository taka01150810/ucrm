<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Purchase;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ItemSeeder::class,
        ]);

        \App\Models\Customer::factory(1000)->create();

        \App\Models\Purchase::factory(100)->create();
        
        /*
        Purchaseを登録時 中間テーブルにも同時に登録する(1件の購入情報に1件～3件の商品情報を登録とする)
        each・・・1件ずつ処理
        attach・・・中間テーブルに情報登録
        外部キー以外で中間テーブルに情報追加するには第2引数に書く 
        */
        
        $items = \App\Models\Item::all();
        
        // use($items) ... 関数の外側の変数を使うことができる
        Purchase::factory(100)->create()
        ->each(function(Purchase $purchase) use ($items){
            $purchase->items()->attach(
                $items->random(rand(1,3))->pluck('id')->toArray(),
                // 1～3個のitemをpurchaseにランダムに紐づけ
                ['quantity' => rand(1, 5) ]
            );
        });
    }
}
