<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BsStorehouse;
use App\Models\BsUsers;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;

class FrontUserController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('用户列表');
            $content->description('');
            $content->body(
                Admin::grid(BsUsers::class, function (Grid $grid) {
                    // 第一列显示id字段，并将这一列设置为可排序列
                    $grid->id('ID')->sortable();
                    // 第二列显示title字段，由于title字段名和Grid对象的title方法冲突，所以用Grid的column()方法代替
                    $grid->column('username', '账号');
                    $grid->column('nickname', '昵称');
                    $grid->column('phone', '手机号码');
                    $grid->column('is_freeze', '状态')->sortable()->display(function ($isFreeze) {
                        return $isFreeze == BsUsers::FROZEN ? '已冻结' : '正常';
                    });
                    $grid->column('updated_at', '更新时间')->display(function ($updatedAt) {
                        return dateFormat($updatedAt);
                    });
                    $grid->column('created_at', '创建时间')->display(function ($createdAt) {
                        return dateFormat($createdAt);
                    });
//                    // 第三列显示director字段，通过display($callback)方法设置这一列的显示内容为users表中对应的用户名
//                    $grid->director()->display(function($userId) {
//                        return User::find($userId)->name;
//                    });
//                    // 第四列显示为describe字段
//                    $grid->describe();
//                    // 第五列显示为rate字段
//                    $grid->rate();
//                    // 第六列显示released字段，通过display($callback)方法来格式化显示输出
//                    $grid->released('上映?')->display(function ($released) {
//                        return $released ? '是' : '否';
//                    });
//                    // 下面为三个时间字段的列显示
//                    $grid->release_at();
//                    $grid->created_at();
//                    $grid->updated_at();
//                    // filter($callback)方法用来设置表格的简单搜索框
//                    $grid->filter(function ($filter) {
//                        // 设置created_at字段的范围查询
//                        $filter->between('created_at', 'Created Time')->datetime();
//                    });
                })
            );
        });
    }

    public function edit(Request $request)
    {

    }
}
