<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BsPurchaseType extends Model
{
    //指定表名
    protected $table = 'bs_purchase_type';
    //指定主键
    protected $primaryKey = 'id';
    //是否开启时间戳
    public $timestamps = false;
    //设置时间戳格式为Unix
    protected $dateFormat = 'U';
    //过滤字段，只有包含的字段才能被更新
    protected $fillable = ['name', 'updated_at'];
    //隐藏字段
//    protected $hidden = ['password'];
    private $validate = [
        //规则
        'rule' => [
            'name'   => 'required | min:1 | max:10 | string | unique',
        ],
        //提示信息
        'message' => [
            'required' => ':attribute不能为空',
            'min'      => ':attribute字数太少了',
            'max'      => ':attribute字数太多了',
            'string'   => ':attribute格式错误',
            'unique'   => ':attribute已经存在',
        ],
        //自定义
        'custom' => [
            'name'   => '采购类型',
        ]
    ];
}
