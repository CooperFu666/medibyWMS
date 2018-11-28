@extends('backend.layouts.header')
<link rel="stylesheet" href="{{ URL('/backend/css') }}/storehouse.css">
<div class="layui-container">
    <div class="layui-row">
        <a href="{{ URL('/admin/storehouseSetting/create') }}">
            <button class="layui-btn layui-btn-sm" {{ URL('/admin/storehouseSetting/create') }}>
                <i class="layui-icon">&#xe654;</i>新增库房
            </button>
        </a>
        <a href="{{ URL('/admin/storehouseSetting/createUser') }}">
            <button class="layui-btn layui-btn-sm" {{ URL('/admin/storehouseSetting/create') }}>
                <i class="layui-icon">&#xe654;</i>新增工作人员
            </button>
        </a>
    </div>

    @foreach ($storehouseList as $storehouse)
    <div class="layui-row storehouse_title_wrapper">
        <div class="text-center layui-col-md9 storehouse_title">{{ $storehouse->name }}</div>
        <div class="text-center layui-col-md3">
            <div class="layui-btn-group">
                <a href="{{ URL('/admin/storehouseSetting/edit?id=' . $storehouse->id) }}">
                    <button class="layui-btn layui-btn-sm">
                        <i class="layui-icon">&#xe642;</i>
                    </button>
                </a>
                <button class="layui-btn layui-btn-sm action_delete" storehouse_id="{{ $storehouse->id }}">
                    <i class="layui-icon">&#xe640;</i>
                </button>
            </div>
        </div>
    </div>
    <div class="layui-row employee_wrapper">
        @foreach ($storehouse->users as $user)
        <div class="layui-col-md3 employee employee-info">
            <div>{{ $user->nickname }}（{{ $user->username }}）</div>
            <div>{{ $user->phone }}</div>
            <div class="layui-btn-group">
                <a href="{{ URL('/admin/storehouseSetting/userEdit?userId=' . $user->id) . '&storehouseId=' . $storehouse->id . '&storehouseName=' . $storehouse->name }}">
                    <button class="layui-btn layui-btn-sm layui-btn-primary">
                        <i class="layui-icon">&#xe642;</i>编辑
                    </button>
                </a>
                @if ($user->is_freeze == \App\Models\BsUsers::FREEZE)
                    <button class="layui-btn layui-btn-sm layui-btn-primary action_is_freeze" user_id="{{ $user->id }}" is_freeze="{{ \App\Models\BsUsers::FROZEN }}">
                        <i class="layui-icon">&#xe6b1;</i>冻结
                    </button>
                @else
                    <button class="layui-btn layui-btn-sm layui-btn-primary text-red action_is_freeze" user_id="{{ $user->id }}" is_freeze="{{ \App\Models\BsUsers::FREEZE }}">
                        <i class="layui-icon">&#xe756;</i>解冻
                    </button>
                @endif
                <button class="layui-btn layui-btn-sm layui-btn-primary action_reset_password" user_id="{{ $user->id }}">
                    <i class="layui-icon">&#xe9aa;</i>重置密码
                </button>
            </div>
        </div>
        <div class="layui-col-md1">&nbsp;</div>
        @endforeach
        <div class="text-center layui-col-md3 employee employee-btn-add">
            <a href="{{ URL('/admin/storehouseSetting/addUserToStoreHouse?storehouseId=' . $storehouse->id . "&storehouseName=" . $storehouse->name) }}">
                <button class="layui-btn layui-btn-sm">
                    <i class="layui-icon">&#xe654;</i>批量添加工作人员
                </button>
            </a>
        </div>
        <div class="layui-col-md1">&nbsp;</div>
    </div>
    @endforeach

    <div class="layui-row storehouse_title_wrapper">
        <div class="text-center layui-col-md9 storehouse_title">未配置库房</div>
    </div>

    <div class="layui-row employee_wrapper">
        @foreach ($notSettingUserList as $user)
        <div class="layui-col-md3 employee employee-info">
            <div>{{ $user->nickname }}（{{ $user->username }}）</div>
            <div>{{ $user->phone }}</div>
            <div class="layui-btn-group">
                <a href="{{ URL('/admin/storehouseSetting/userEdit?userId=' . $user->id) . '&storehouseId=' . $storehouse->id . '&storehouseName=' . $storehouse->name }}">
                    <button class="layui-btn layui-btn-sm layui-btn-primary">
                        <i class="layui-icon">&#xe642;</i>编辑
                    </button>
                </a>
                @if ($user->is_freeze == \App\Models\BsUsers::FREEZE)
                <button class="layui-btn layui-btn-sm layui-btn-primary action_is_freeze" user_id="{{ $user->id }}" is_freeze="{{ \App\Models\BsUsers::FROZEN }}">
                    <i class="layui-icon">&#xe6b1;</i>冻结
                </button>
                @else
                <button class="layui-btn layui-btn-sm layui-btn-primary text-red action_is_freeze" user_id="{{ $user->id }}" is_freeze="{{ \App\Models\BsUsers::FREEZE }}">
                    <i class="layui-icon">&#xe756;</i>解冻
                </button>
                @endif
                <button class="layui-btn layui-btn-sm layui-btn-primary action_reset_password" user_id="{{ $user->id }}">
                    <i class="layui-icon">&#xe9aa;</i>重置密码
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
    script.src = "{{ URL('/backend/js/storehouse.js') }}";
    document.body.appendChild(script);
</script>