@extends('backend.layouts.header')
<link rel="stylesheet" href="{{ URL('/backend/css/company.css') }}">
<div class="layui-container">
    <div class="layui-row">
        <a href="{{ URL('/admin/companySetting/companyCreate') }}">
            <button class="layui-btn layui-btn-sm">
                <i class="layui-icon">&#xe654;</i>新增销售公司
            </button>
        </a>
        <a href="{{ URL('/admin/companySetting/createSeller') }}">
            <button class="layui-btn layui-btn-sm">
                <i class="layui-icon">&#xe654;</i>新增销售代表
            </button>
        </a>
    </div>

    @foreach ($companyList as $company)
    <div class="layui-row storehouse_title_wrapper">
        <div class="text-center layui-col-md9 storehouse_title">{{ $company->name }}</div>
        <div class="text-center layui-col-md3">
            <div class="layui-btn-group">
                <a href="{{ URL("/admin/companySetting/{$company->id}/{$company->name}/companyEdit") }}">
                    <button class="layui-btn layui-btn-sm">
                        <i class="layui-icon">&#xe642;</i>
                    </button>
                </a>
                <button class="layui-btn layui-btn-sm action_company_delete" company_id="{{ $company->id }}" company_name="{{ $company->name }}">
                    <i class="layui-icon">&#xe640;</i>
                </button>
            </div>
        </div>
    </div>
    <div class="layui-row employee_wrapper">
        @foreach ($company->seller as $seller)
        <div class="layui-col-md3 employee employee-info">
            <div>{{ $seller->name }}</div>
            <div class="layui-btn-group">
                <a href="{{ URL("/admin/companySetting/editSeller?sellerId={$seller->id}&sellerName={$seller->name}&companyId={$company->id}&companyName={$company->name}") }}">
                    <button class="layui-btn layui-btn-sm layui-btn-primary">
                        <i class="layui-icon">&#xe642;</i>修改
                    </button>
                </a>
                <button class="layui-btn layui-btn-sm layui-btn-primary action_seller_delete" seller_id="{{ $seller->id }}" seller_name="{{ $seller->name }}">
                    <i class="layui-icon">&#xe9aa;</i>删除
                </button>
            </div>
        </div>
        <div class="layui-col-md1">&nbsp;</div>
        @endforeach
        <div class="text-center layui-col-md3 employee employee-btn-add">
            <a href="{{ URL('/admin/companySetting/addSeller?companyId=' . $company->id . "&companyName=" . $company->name) }}">
                <button class="layui-btn layui-btn-sm">
                    <i class="layui-icon">&#xe654;</i>批量添加销售代表
                </button>
            </a>
        </div>
        <div class="layui-col-md1">&nbsp;</div>
    </div>
    @endforeach

    @if (count($notSettingSellerList) > 0)
        <div class="layui-row storehouse_title_wrapper">
            <div class="text-center layui-col-md9 storehouse_title">未配置人员</div>
        </div>

        <div class="layui-row employee_wrapper">
            @foreach ($notSettingSellerList as $seller)
                <div class="layui-col-md3 employee employee-info">
                    <div>{{ $seller->name }}</div>
                    <div class="layui-btn-group">
                        <a href="{{ URL("/admin/companySetting/editSeller?sellerId={$seller->id}&sellerName={$seller->name}") }}">
                            <button class="layui-btn layui-btn-sm layui-btn-primary">
                                <i class="layui-icon">&#xe642;</i>修改
                            </button>
                        </a>
                        <button class="layui-btn layui-btn-sm layui-btn-primary action_seller_delete" seller_id="{{ $seller->id }}" seller_name="{{ $seller->name }}">
                            <i class="layui-icon">&#xe9aa;</i>删除
                        </button>
                    </div>
                </div>
                <div class="layui-col-md1">&nbsp;</div>
            @endforeach
        </div>
    @endif
</div>
<script>
script = document.createElement('script');
script.type = "text/javascript";
script.src = "{{ URL('/backend/js/company.js') }}";
document.body.appendChild(script);
</script>