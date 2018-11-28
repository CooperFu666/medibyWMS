@extends('frontend.base')
@section('content')
<link rel="stylesheet" href="{{ URL('/frontend') }}/css/common.css" />
<link rel="stylesheet" href="{{ URL('/frontend') }}/css/goodsManage.css" />
<!--主体start-->
<div class="main">
    <h2>商品管理</h2>
    <form action="{{ URL('/home/goodsManage') }}" method="get">
        <div class="term">
                <div class="termSub">
                    <span>品牌</span>
                    <input id="search_brand" name="search[brand]" type="text" class="seled" value="{{ issetParams(['search' => 'brand'], '全部') }}" readonly="readonly">
                    <ul id="brand_list" class="action_ul_refresh">
                        <li>全部</li>
                    </ul>
                </div>
                <div class="termSub">
                    <span>状态</span>
                    <input id="search_status" name="search[status]" type="text" class="seled" value="{{ issetParams(['search' => 'status'], '全部') }}" readonly="readonly">
                    <ul class="action_ul_refresh">
                        <li>全部</li>
                        <li>已出库</li>
                        <li>库存</li>
                    </ul>
                </div>
                <div class="termSub">
                    <span>公司</span>
                    <input id="search_company" name="search[company]" type="text" class="seled" value="{{ issetParams(['search' => 'company'], '全部') }}" readonly="readonly">
                    <ul class="action_ul_refresh">
                        <li class="action_select_company">全部</li>
                        @foreach ($companyList as $company)
                            <li class="action_select_company" company_id="{{ $company->id }}">{{ $company->name }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="termSub" id="select_seller">
                    <span>销售代表</span>
                    <input id="search_seller" name="search[seller]" type="text" class="seled_id" value="{{ issetParams(['search' => 'seller'], '全部') }}" readonly="readonly">
                    <input id="search_seller_id" name="search[seller_id]" type="text" value="" hidden>
                    <ul class="action_ul_refresh">
                        <li class="action_select_seller">全部</li>
                        @foreach ($sellerList as $sellerId => $sellerName)
                        <li class="action_select_seller" seller_id="{{ $sellerId }}">{{ $sellerName }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="termSub fl_r">
                    <input id="search_time_type" name="search[time_type]" type="text" class="seled " value="{{ issetParams(['search' => 'time_type'], '按入库时间') }}" readonly="readonly">
                    <ul>
                        <li>按入库时间</li>
                        <li>按出库时间</li>
                    </ul>
                    <input id="search_time" name="search[time]" type="text" class="timer" value="{{ issetParams(['search' => 'time'], '') }}"/>
                    <button type="submit">查询</button>
                </div>
        </div>
        <div class="term">
            <div class="termSub w_470">
                <span>客户所在地</span>
                <div id="distPicker">
                    <div class="item" id="Province">
                        <label class="sr-only" for="province">Province</label>
                        <select class="form-control action_select_refresh" id="province" name="cho_Province" value="{{ issetParams('cho_Province', '省') }}" ></select>
                    </div>
                    <div class="item" id="City">
                        <label class="sr-only" for="city">City</label>
                        <select class="form-control action_select_refresh" id="city" name="cho_City" value="{{ issetParams('cho_City', '市') }}"></select>
                    </div>
                    <div class="item" id="Area">
                        <label class="sr-only" for="district">District</label>
                        <select class="form-control action_select_refresh" id="district" name="cho_Area" value="{{ issetParams('cho_Area', '区') }}"></select>
                    </div>
                </div>
            </div>
            <div class="termSub customer">
                <span>客户</span>
                <input id="search_client" name="search[client]" type="text" class="seled" value="{{ issetParams(['search' => 'client'], '全部') }}" readonly="readonly">
                <ul class="action_ul_refresh">
                    <li>全部</li>
                    @foreach ($clientList as $client)
                    <li>{{ $client->name }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="termSub">
                <input id="search_no" name="search[no]" type="text" placeholder="请输入型号/批号/合同号/注册证号" value="{{ issetParams(['search' => 'no'], '') }}" class="timer" style="width: 280px;" />
                <button type="submit" class="action_submit">搜索</button>
            </div>
            <input name="export" disabled="disabled" hidden>
            <button type="submit" class="export" id="export">导出</button>
        </div>
    </form>
    <div style="overflow: auto; width: 100%;">
        <table class="gridtable" id="alternatecolor">
            <tr>
                <th>货号</th>
                <th>库房</th>
                <th>型号</th>
                <th>品牌</th>
                <th>批号</th>
                <th>生产日期/有效期</th>
                <th>注册证号</th>
                <th>采购合同号</th>
                <th>入库时间</th>
                <th>入库操作者</th>
                <th>销售合同号</th>
                <th>客户</th>
                <th>出库时间</th>
                <th>出库操作者</th>
            </tr>
            @foreach ($goodsList as $goods)
            <tr>
                <td>{{ $goods->deal_number }}</td>
                <td>{{ $goods->storehouse }}</td>
                <td>{{ $goods->version }}</td>
                <td>{{ $goods->brand }}</td>
                <td>{{ $goods->batch_no }}</td>
                <td>{{ dateFormat($goods->valid_at, 'Y-m-d') }}</td>
                <td>{{ ifValueEmpty($goods->reg_no) }}</td>
                <td>{{ ifValueEmpty($goods->purchase_contract_no) }}</td>
                <td>{{ dateFormat($goods->storage_at) }}</td>
                <td>{{ $goods->storage_operator }}</td>
                <td>{{ ifValueEmpty($goods->sale_contract_no) }}</td>
                <td>
                    @if (!empty($goods->client))
                        {{ $goods->client }}
                        <i client="{{ $goods->client }}" client_province="{{ $goods->client_province }}" client_city="{{ $goods->client_city }}" client_district="{{ $goods->client_district }}">?</i>
                    @else
                        --
                    @endif
                </td>
                <td>{{ !empty($goods->out_at) ? dateFormat($goods->out_at): '--' }}</td>
                <td>{{ ifValueEmpty($goods->out_operator) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    {{ $goodsList->links() }}
</div>
<!--主体end-->

<!--弹出框start-->
<div class="popUp client">
    <div class="popUpBox">
        <h2>
            <span>客户</span>
            <i class="PopUpClose">×</i>
        </h2>
        <div class="popUpBoxTest">
            <p id="client"></p>
            <p id="address"></p>
            <button class="PopUpClose">确定</button>
        </div>
    </div>
</div>
<!--弹出框end-->
<script>
    var companyList = {!! json_encode($companyList) !!};
</script>
@include('frontend.layouts.footer')
<script src="{{ URL('/frontend/js/goodsManage.js')  }}"></script>
<script type="text/javascript" src="{{ URL('/common/plugins/distpicker/js/distpicker.js') }}"></script>
<script>
    // altRows('pupupcolor');
    $(function () {
        $('#distPicker').distpicker({
            province: '{{ issetParams("cho_Province", "请选择省份") }}',
            city: '{{ issetParams("cho_City", "请选择城市") }}',
            district: '{{ issetParams("cho_Area", "请选择地区") }}',
            autoSelect: false,
        });
    });
</script>
@endsection