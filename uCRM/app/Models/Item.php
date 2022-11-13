<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
