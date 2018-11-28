<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>医加仓储系统-Mediby WMS</title>
        <link rel="stylesheet" href="{{ URL('/frontend') }}/plugins/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ URL('/frontend') }}/css/common.css">
        <link rel="stylesheet" href="{{ URL('/common') }}/plugins/layui2.4.3/css/layui.css">
        <script src="{{ URL('/common')  }}/plugins/layui2.4.3/layui.all.js"></script>
        <script src="{{ URL('/common')  }}/js/config.js"></script>
        <script src="{{ URL('/common')  }}/js/common.js"></script>
        <script src="{{ URL('/common')  }}/js/jquery-3.2.1.js"></script>
        <script src="{{ URL('/frontend')  }}/js/goodsInWarehouse.js"></script>
        <script src="{{ URL('/frontend')  }}/js/common.js"></script>
    </head>
    <body>
    {{-- 继承后插入的内容 --}}
    @yield('content')
    </body>
</html>