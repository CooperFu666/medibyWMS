<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BsGoods extends Model
{
//    const PURCHASE_TYPE_DOMESTIC = 1;                   //国内调货
//    const PURCHASE_TYPE_FOREIGN = 2;                    //国际采购
//    const PURCHASE_TYPE_EXCHANGE = 3;                   //退换货入库

    const STATUS_IN = 1;    //库存中
    const STATUS_OUT = 2;   //已出库

    const DELETE_NOT = 0;   //未删除
    const DELETED = 1;      //已删除(撤销入库)


//    public static $purchaseTypeArr = [
//        self::PURCHASE_TYPE_DOMESTIC => '国内调货',
//        self::PURCHASE_TYPE_FOREIGN => '国际采购',
//        self::PURCHASE_TYPE_EXCHANGE => '退换货入库',
//    ];

    //指定表名
    protected $table = 'bs_goods';
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
//        'storage_at' => 'datetime',
//        'valid_at' => 'datetime',
    ];

    public function records()
    {
        return $this->belongsTo(BsRecords::class, 'records_id', 'id');
        // 第一个参数为关联的模型名字，第二个参数为外键，第三个参数为主键
    }

    public function belong()
    {
        return $this->belongsTo(BsRecordsGoodsRelations::class, 'goods_deal_number', 'deal_number');
        // 第一个参数为关联的模型名字，第二个参数为外键，第三个参数为主键
    }
}
