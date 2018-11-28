<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BsStorehouse extends Model
{
    //指定表名
    protected $table = 'bs_storehouse';
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
            'name'   => 'required | min:3 | max:50 | string | unique',
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
            'name'   => '库房名',
        ]
    ];

    public function users()
    {
        return $this->hasMany(BsUsers::class, 'storehouse_id', 'id');
        // 第一个参数为关联的模型名字，第二个参数为外键，第三个参数为主键
    }
}
