<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Purchase;

class Item extends Model
{
    use HasFactory;

    // コントローラ側にて Item::create() で保存できるようにモデル側に追記
    protected $fillable = [
        'name',
        'memo',
        'price',
        'is_selling'
    ];

    // 中間テーブル内の情報を取得したいため、 withPivotも使用 
    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)
        ->withPivot('quantity');
    }
}
