<div class="header">
    <div class="logo"><img src="{{ URL('/frontend/images/logo.png') }}" alt=""></div>
    <ul class="nav">
        <a href="{{ URL('/home/goodsIn') }}">
            <li class="{{ strpos(URL::current(), 'goodsIn')?'active':'' }}">
                <img src="{{ URL('/frontend/images/store') }}{{ strpos(URL::current(), 'goodsIn')?'_cut':'' }}.png" alt="">
                <p>商品入库</p>
            </li>
        </a>
        <a href="{{ URL('/home/goodsOut') }}">
            <li class="{{ strpos(URL::current(), 'goodsOut')?'active':'' }}">
                <img src="{{ URL('/frontend/images/storege') }}{{ strpos(URL::current(), 'goodsOut')?'_cut':'' }}.png" alt="">
                <p>商品出库</p>
            </li>
        </a>
        <a href="{{ URL('/home/goodsManage') }}">
            <li class="{{ strpos(URL::current(), 'goodsManage')?'active':'' }}">
                <img src="{{ URL('/frontend/images/gdmanage') }}{{ strpos(URL::current(), 'goodsManage')?'_cut':'' }}.png" alt="">
                <p>商品管理</p>
            </li>
        </a>
        <a href="{{ URL('/home/inOutRecords') }}">
            <li class="{{ strpos(URL::current(), 'inOutRecords')?'active':'' }}">
                <img src="{{ URL('/frontend/images/ssge') }}{{ strpos(URL::current(), 'inOutRecords')?'_cut':'' }}.png" alt="">
                <p>出入库记录</p>
            </li>
        </a>
        {{--<a href="{{ URL('') }}">--}}
            {{--<li>--}}
                {{--<img src="{{ URL('/frontend') }}/images/numanage.png" alt="">--}}
                {{--<p>账号管理</p>--}}
            {{--</li>--}}
        {{--</a>--}}
        <li id="changePassword">
            <img src="{{ URL('/frontend') }}/images/revise.png" alt="">
            <p>修改密码</p>
        </li>
        <a href="{{ route('logout') }}" onclick="isLogout()">
            <li>
                <img src="{{ URL('/frontend') }}/images/quit.png" alt="">
                <p>
                    安全退出
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </p>
            </li>
        </a>
    </ul>
</div>