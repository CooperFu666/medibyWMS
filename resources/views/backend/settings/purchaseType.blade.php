@extends('backend.layouts.header')
<link rel="stylesheet" href="{{ URL('/backend/css') }}/purchaseType.css">
<div class="layui-container">
    <div class="layui-row">
        <a href="{{ URL('/admin/purchaseTypeSetting/create') }}">
            <button class="layui-btn layui-btn-sm" {{ URL('/admin/purchaseTypeSetting/create') }}>
                <i class="layui-icon">&#xe654;</i>新增采购类型
            </button>
        </a>
    </div>
    <div class="layui-row employee_wrapper">
        @foreach ($purchaseTypeList as $purchaseType)
        <div class="layui-col-md2 employee employee-info text-center">
            <div>{{ $purchaseType->name }}</div>
            <div class="layui-btn-group">
                <a href="{{ URL("/admin/purchaseTypeSetting/{$purchaseType->id}/edit") }}">
                    <button class="layui-btn layui-btn-sm layui-btn-primary">
                        <i class="layui-icon">&#xe642;</i>修改
                    </button>
                </a>
                <button class="layui-btn layui-btn-sm layui-btn-primary action_purchase_type_delete"  purchaseTypeId="{{ $purchaseType->id }}" purchaseTypeName="{{ $purchaseType->name }}">
                    <i class="layui-icon">&#xe640;</i>删除
                </button>
            </div>
        </div>
        <div class="layui-col-md1">&nbsp;</div>
        @endforeach
    </div>
</div>
<script>
    script = document.createElement('script');
    script.type = "text/javascript";
    script.src = "{{ URL('/backend/js/purchaseType.js') }}";
    document.body.appendChild(script);
</script>