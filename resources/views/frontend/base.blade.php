<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>医加仓储系统-Mediby WMS</title>
        <link rel="stylesheet" href="{{ URL('/frontend/plugins/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ URL('/frontend/css/common.css') }}">
        <link rel="stylesheet" href="{{ URL('/common/plugins/layui2.4.3/css/layui.css') }}">
    </head>
    <body>
    {{-- 包含页头 --}}
    @include('frontend.layouts.header')

    {{-- 继承后插入的内容 --}}
    @yield('content')
    </body>
</html>