<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BsCompany extends Model
{
    //指定表名
    protected $table = 'bs_company';
    //指定主键
    protected $primaryKey = 'id';
    //是否开启时间戳
    public $timestamps = false;
    //设置时间戳格式为Unix
    protected $dateFormat = 'U';
    //过滤字段，只有包含的字段才能被更新
    protected $fillable = ['name', 'updated_at'];
    //隐藏字段
    //protected $hidden = ['password'];

    public function seller()
    {
        return $this->hasMany(BsSeller::class, 'company_id', 'id');
        // 第一个参数为关联的模型名字，第二个参数为外键，第三个参数为主键
    }
}
