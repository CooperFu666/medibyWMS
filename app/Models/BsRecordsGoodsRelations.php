<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BsRecordsGoodsRelations extends Model
{
    //指定表名
    protected $table = 'bs_records_goods_relations';
    //指定主键
    protected $primaryKey = 'id';
    //是否开启时间戳
    public $timestamps = false;
    //设置时间戳格式为Unix
    protected $dateFormat = 'U';
    //过滤字段，只有包含的字段才能被更新
//    protected $fillable = ['records_id', 'goods_id'];
    //隐藏字段
//    protected $hidden = ['password'];

    //查询时字段类型格式
    protected $casts = [
//        'created_at' => 'datetime',
    ];

    public function goods()
    {
        return $this->hasMany(BsGoods::class, 'deal_number', 'goods_deal_number');
        // 第一个参数为关联的模型名字，第二个参数为外键，第三个参数为主键
    }

    public function records()
    {
        return $this->hasOne(BsRecords::class, 'id', 'records_id');
        // 第一个参数为关联的模型名字，第二个参数为外键，第三个参数为主键
    }
}
