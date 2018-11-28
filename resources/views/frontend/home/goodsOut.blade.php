@extends('frontend.base')
@section('content')
<link rel="stylesheet" href="{{ URL('/frontend/css/goodsOutWarehouse.css') }}">
<div class="content" style="z-index: 999">
    <!--头部strat-->
	<div id="header"></div>
	<!--头部end-->
    <!--内容-->
    <div class="contentMain">
        <div class="section">
            <h2>出库列表</h2>
            <div class="checkMenu">
                <div class="contract">
                    <label>销售合同号</label>
                    <input id="sale_contract_no" type="text" placeholder="请输入">
                </div>
                <div class="contract">
                    <label>物流单号</label>
                    <input id="express_no" type="text" placeholder="请输入">
                </div>
                <br>
                <div class="client">
                    <label>客户</label>
                    <input id="client" type="text"  class="simulation selected Monitor" placeholder="请选择">
                    <input id="client_id" type="text" hidden>
                    <ul class="check checkList action_select_client">
                        @foreach($clientList as $clientId => $clientName)
                            <li clientId="{{ $clientId }}">{{ $clientName }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="base">
                    <label>销售公司</label>
                    <input id="company" type="text"  class="simulation selected Monitor" autocomplete="off" readonly="readonly"  placeholder="请选择">
                    <input id="company_id" type="text" hidden>
                    <ul class="check checkList action_select_company">
                        @foreach($companyList as $company)
                        <li companyId="{{ $company->id }}">{{$company->name}}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="category">
                    <label>销售代表</label>
                    <input id="seller" type="text" placeholder="请选择" readonly="readonly" class="simulation selected" >
                    <input id="seller_id" type="text" hidden>
                    <ul class="check checkList">
                    </ul>
                </div>
            </div>
            <div class="Remarks">
                <label>出库备注</label>
                <textarea id="remark" rows="7" placeholder="请输入备注"></textarea>
            </div>
            <table class="goodsList">
                <tbody>
                    <tr>
                        <th>货号</th>
                        <th>品牌</th>
                        <th>批号</th>
                        <th>生产日期/有效期</th>
                        <th>注册证号</th>
                        <th>采购合同号</th>
                        <th>操作</th>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="submitBox">
            <button class="btn DetermineInWarehousing" type="submit">确定出库</button>
            {{--<p>统计：ABC-001 X10</p>--}}
        </div>
    </div>
</div>

<table id="tr_wrapper" hidden>
    <tbody>
        <tr>
            <td id="deal_number_hidden"></td>
            <td id="brand_hidden"></td>
            <td id="batch_no_hidden"></td>
            <td id="valid_at_hidden"></td>
            <td id="reg_no_hidden"></td>
            <td id="purchase_contract_no_hidden"></td>
            <td><a class="delete">删除</a></td>
        </tr>
    </tbody>
</table>

<!--弹出框-->
<div class="alertBox isAdd">
    <div class="alertContent">
        <h3>提示 <span class="closeAlert">X</span></h3>
        <div>
            <p>数据库里没有<span class="action_is_add_company"></span>，是否添加为客户？</p>
            <div class="btn_box">
                <button class="btn Determine determineAdd"><i class="fa fa-check"></i> 确定</button>
                <button class="return"><i class="fa fa-reply"></i> 取消</button>
            </div>
        </div>
    </div>
</div>

<div class="alertBox successAdd">
    <div class="alertContent">
        <h3>添加客户 <span class="closeAlert">X</span></h3>
        <div>
            <p>已成功添加<span class="action_is_add_company"></span>，<span class="action_is_add_address"></span></p>
            <div class="btn_box local">
                <button class="btn Determine successBtn"><i class="fa fa-check"></i> 确定</button>
            </div>
        </div>
    </div>
</div>

<div class="alertBox customerAddressBox">
    <div class="alertContent customerAddress">
        <h3>添加客户 <span class="closeAlert">X</span></h3>
        <div class="input-group">
            <div id="distPicker">
                <div class="form-group col-md-4" style="margin-bottom: 0;">
                    <label class="sr-only" for="province">Province</label>
                    <select class="form-control" id="province" name="province"></select>
                </div>
                <div class="form-group col-md-4"  style="margin-bottom: 0;">
                    <label class="sr-only" for="city">City</label>
                    <select class="form-control" id="city" name="city"></select>
                </div>
                <div class="form-group col-md-4"  style="margin-bottom: 0;">
                    <label class="sr-only" for="district">District</label>
                    <select class="form-control" id="district" name="district"></select>
                </div>
            </div>
        </div>
        <div class="btn_box checkRegion">
            <button class="btn Determine determineAddress" type="button"><i class="fa fa-check"></i> 确定</button>
        </div>
    </div>
</div>
{{-- 包含页脚 --}}
@include('frontend.layouts.footer')
<script type="text/javascript" src="{{ URL('/common/plugins/distpicker/js/distpicker.js') }}"></script>
<script type="text/javascript" src="{{ URL('/common/plugins/distpicker/js/main.js') }}"></script>
<script>
    var companyList = {!! json_encode($companyList) !!};
</script>
<script src="{{ URL('/frontend/js/goodsOutWarehouse.js')  }}"></script>
@endsection
