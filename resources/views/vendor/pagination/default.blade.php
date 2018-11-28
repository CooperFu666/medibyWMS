@if ($paginator->hasPages())
    <div class="paging">
        <div class="pagingInfo">
            <span>总计 {{ $paginator->total() }} 个记录</span>
            <span>分为 {{ $paginator->lastPage() }} 页</span>
            <span>当前第 {{ $paginator->currentPage() }} 页，每页 {{ $paginator->perPage() }} 个记录</span>
        </div>
        <div class="pagingOperate">
            <a href="{{ $paginator->url(1) }}" rel="prev"><button class="prev" onclick="javascript:window.location.href='{{ $paginator->url(1) }}'">首页</button></a>
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="prev" style="background: #ccc">上一页</button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"><button class="prev" onclick="javascript:window.location.href='{{ $paginator->previousPageUrl() }}'">上一页</button></a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"><button class="next" onclick="javascript:window.location.href='{{ $paginator->nextPageUrl() }}'">下一页</button></a>
            @else
                <button class="prev" style="background: #ccc">下一页</button>
            @endif
            <a href="{{ $paginator->url($paginator->lastPage()) }}" rel="prev"><button class="prev" onclick="javascript:window.location.href='{{ $paginator->url($paginator->lastPage()) }}'">尾页</button></a>
            <p style="padding-right: 10px;">跳转到<input id="pagination_jump_page" type="text" />页</p>
            <button onclick="pagination_jump_page_submit()">确定</button>
            <script>
                function pagination_jump_page_submit() {
                    window.location.href = changeURLArg(window.location.href, 'page', $('#pagination_jump_page').val());
                }
            </script>
        </div>
    </div>


    {{--<ul class="pagination">--}}
        {{-- Previous Page Link --}}
        {{--@if ($paginator->onFirstPage())--}}
            {{--<li class="disabled"><span>&laquo;</span></li>--}}
        {{--@else--}}
            {{--<li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>--}}
        {{--@endif--}}

        {{-- Pagination Elements --}}
        {{--@foreach ($elements as $element)--}}
            {{-- "Three Dots" Separator --}}
            {{--@if (is_string($element))--}}
                {{--<li class="disabled"><span>{{ $element }}</span></li>--}}
            {{--@endif--}}

            {{-- Array Of Links --}}
            {{--@if (is_array($element))--}}
                {{--@foreach ($element as $page => $url)--}}
                    {{--@if ($page == $paginator->currentPage())--}}
                        {{--<li class="active"><span>{{ $page }}</span></li>--}}
                    {{--@else--}}
                        {{--<li><a href="{{ $url }}">{{ $page }}</a></li>--}}
                    {{--@endif--}}
                {{--@endforeach--}}
            {{--@endif--}}
        {{--@endforeach--}}

        {{-- Next Page Link --}}
        {{--@if ($paginator->hasMorePages())--}}
            {{--<li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>--}}
        {{--@else--}}
            {{--<li class="disabled"><span>&raquo;</span></li>--}}
        {{--@endif--}}
    {{--</ul>--}}
@endif
