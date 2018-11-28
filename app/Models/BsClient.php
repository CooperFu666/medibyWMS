<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BsClient extends Model
{
    //指定表名
    protected $table = 'bs_client';
    //指定主键
    protected $primaryKey = 'id';
    //是否开启时间戳
    public $timestamps = false;
    //设置时间戳格式为Unix
    protected $dateFormat = 'U';
    //过滤字段，只有包含的字段才能被更新
    protected $fillable = [];
    //隐藏字段
    //protected $hidden = ['password'];
}
