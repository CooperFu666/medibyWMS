@extends('backend.layouts.header')
<style>
    .storehouse_title {
        background: #009688;
        color: #FFF;
        height: 30px;
        line-height: 30px;
        border-radius: 2px;
    }
    .storehouse_title_wrapper {
        margin-top: 30px;
    }
    .employee {
        border: solid 1px #009688;
        height: 120px;
        margin-top: 10px;
    }
    .employee-btn-add {
        line-height: 120px;

    }
    .employee-info {
        padding-left: 10px;
        padding-top: 10px;
    }
    .employee-info div {
        margin-top: 7px;
        color: #009688;
    }
    .employee-info div button{
        color: #009688;
    }
    .layui-btn {
        color: #0d6aad;
    }
    .client-btn-delete {
        float: right;
        margin-right: 15px;
    }
</style>
<div class="layui-container">
    <div class="layui-row">
        <button class="layui-btn layui-btn-sm" {{ URL('/admin/settings/storehouseAction', ['action' => 'add']) }}>
            <i class="layui-icon">&#xe654;</i>新增客户
        </button>
    </div>
    <div class="layui-row employee_wrapper">
        <div class="layui-col-md3 employee employee-info">
            <div>XXXXXXXXXX公司</div>
            <div>四川-成都-金牛区</div>
            <div>
                <button class="layui-btn layui-btn-sm layui-btn-primary client-btn-delete">
                    <i class="layui-icon">&#xe640;</i>删除
                </button>
            </div>
        </div>
        <div class="layui-col-md1">&nbsp;</div>
    </div>
</div>