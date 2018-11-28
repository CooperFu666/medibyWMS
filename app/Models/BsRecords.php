<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BsRecords extends Model
{
    const CANCELLED = 1;            //已撤销
    const CANCELED_NOT = 0;         //正常
    const TYPE_IN = 1;              //入库
    const TYPE_OUT = 2;             //出库

    public static $typeArr = [
        self::TYPE_IN => '入库',
        self::TYPE_OUT => '出库',
    ];

    //指定表名
    protected $table = 'bs_records';
    //指定主键
    protected $primaryKey = 'id';
    //是否开启时间戳
    public $timestamps = false;
    //设置时间戳格式为Unix
    protected $dateFormat = 'U';
    //过滤字段，只有包含的字段才能被更新
//    protected $fillable = ['name', 'updated_at'];
    //隐藏字段
//    protected $hidden = ['password'];

    //查询时字段类型格式
    protected $casts = [
//        'created_at' => 'datetime',
    ];

    public function goods()
    {
        return $this->belongsToMany(BsGoods::class, 'bs_records_goods_relations', 'records_id', 'goods_deal_number', 'id', 'deal_number')
            ->as('records');
        // 第一个参数为关联的模型名字，第二个参数为外键，第三个参数为主键
    }
//    public static function belong()
//    {
//        return $this->belongsTo(BsRecordsGoodsRelations::class, 'records_id', 'id');
//        // 第一个参数为关联的模型名字，第二个参数为外键，第三个参数为主键
//    }
}
