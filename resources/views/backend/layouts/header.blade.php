<link rel="stylesheet" href="{{ URL('/common/plugins/layui2.4.3/css/layui.css') }}">
<script type="text/javascript" src="{{ URL('/common/plugins/layui2.4.3/layui.all.js')  }}"></script>
<script type="text/javascript" src="{{ URL('/common/js/config.js')  }}"></script>
<script type="text/javascript" src="{{ URL('/backend/js/config.js')  }}"></script>
<script type="text/javascript" src="{{ URL('/common/js/common.js')  }}"></script>
{{-- 继承后插入的内容 --}}
@yield('content')
