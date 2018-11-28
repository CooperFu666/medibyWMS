@extends('frontend.base')
@section('content')
<link rel="stylesheet" href="{{ URL('/frontend') }}/css/common.css" />
<link rel="stylesheet" href="{{ URL('/frontend') }}/css/inOutRecords.css" />
<!--头部strat-->
<div id="header"></div>
<!--头部end-->
<!--主体strat-->
<div class="main">
	<h2>出入库记录</h2>
    <form action="{{ URL('/home/inOutRecords') }}" method="get">
        <div class="term">
            <div class="termSub">
                <span>类型</span>
                <input name="search[type]" type="text" class="seled mold" value="{{ issetParams(['search' => 'type'], '全部') }}" data-type="" readonly="readonly">
                <ul class="moldUl action_ul_refresh">
                    <li>全部</li>
                    <li>入库</li>
                    <li>出库</li>
                </ul>
                <script>
                    var type = '{{ issetParams(['search' => 'type'], '全部') }}';
                </script>
            </div>
            <div class="termSub downHide">
                <span>公司</span>
                <input id="search_company" name="search[company]" type="text" class="seled" value="{{ issetParams(['search' => 'company'], '全部') }}" readonly="readonly">
                <ul class="action_ul_refresh">
                    <li class="action_select_company">全部</li>
                    @foreach ($companyList as $company)
					<li class="action_select_company" company_id="{{ $company->id }}">{{ $company->name }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="termSub downHide" id="select_seller">
                <span>销售代表</span>
                <input id="search_seller" name="search[seller]" type="text" class="seled" value="{{ issetParams(['search' => 'seller'], '全部') }}" readonly="readonly">
				<ul class="action_ul_refresh">
					<li class="action_select_seller">全部</li>
					@foreach ($sellerList as $sellerId => $sellerName)
                    <li class="action_select_seller" seller_id="{{ $sellerId }}">{{ $sellerName }}</li>
					@endforeach
				</ul>
            </div>
            <div class="termSub downHide w_470">
                <span>客户所在地</span>
				<div id="distPicker">
					<div class="item" id="Province">
						<label class="sr-only" for="province">Province</label>
						<select class="form-control action_select_refresh" id="province" name="cho_Province" value="{{ issetParams('cho_Province', '请选择省份') }}" ></select>
					</div>
					<div class="item" id="City">
						<label class="sr-only" for="city">City</label>
						<select class="form-control action_select_refresh" id="city" name="cho_City" value="{{ issetParams('cho_City', '请选择城市') }}"></select>
					</div>
					<div class="item" id="Area">
						<label class="sr-only" for="district">District</label>
						<select class="form-control action_select_refresh" id="district" name="cho_Area" value="{{ issetParams('cho_Area', '请选择地区') }}"></select>
					</div>
				</div>
            </div>

            <div class="termSub customer downHide">
                <span>客户</span>
                <input name="search[client]" type="text" class="seled" value="{{ issetParams(['search' => 'client'], '全部') }}" readonly="readonly">
                <ul class="action_ul_refresh">
                    <li>全部</li>
                    @foreach ($clientList as $clientId => $clientName)
                        <li>{{ $clientName }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="termSub goodsIn">
                <span>采购类别</span>
                <input name="search[purchase_type]" type="text" class="seled" value="{{ issetParams(['search' => 'purchase_type'], '全部') }}" readonly="readonly">
                <ul class="action_ul_refresh">
                    <li>全部</li>
					@foreach ($purchaseTypeList as $purchaseTypeId => $purchaseTypeName)
						<li purchaseTypeId="{{ $purchaseTypeId }}">{{ $purchaseTypeName }}</li>
					@endforeach
                </ul>
            </div>
        </div>
        <div class="term upHide">
            <div class="termSub">
                <span>操作者</span>
                <input name="search[operator]" type="text" class="seled runner" value="{{ issetParams(['search' => 'operator'], '全部') }}" readonly="readonly">
                <ul class="runnerUl action_ul_refresh">
                    <li>全部</li>
                    @foreach ($userList as $user)
                        <li>{{ $user->nickname }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="termSub">
                <span>包含品牌</span>
                <input name="search[brand]" type="text" class="seled" value="{{ issetParams(['search' => 'brand'], '全部') }}" readonly="readonly">
                <ul id="brand_list" class="action_ul_refresh">
                    <li>全部</li>
                </ul>
            </div>
            <div class="termSub fl_l">
                <input name="search[time_type]" type="text" class="seled" value="按出入库时间" readonly="readonly">
                <ul>
                    <li>按出入库时间</li>
                </ul>
                <input id="search_time" name="search[time]" type="text" class="timer" value="{{ issetParams(['search' => 'time'], '') }}"/>
                <button type="submit">查询</button>
            </div>
            <div class="termSub">
                <span>状态</span>
                <input name="search[is_cancel]" type="text" class="seled" value="{{ issetParams(['search' => 'is_cancel'], '全部') }}" readonly="readonly">
                <ul class="action_ul_refresh">
                    <li>全部</li>
                    <li>正常</li>
                    <li>已撤销</li>
                </ul>
            </div>
            <div class="termSub fl_r">
                <input name="search[no]" type="text" placeholder="请输入合同号/型号" value="{{ issetParams(['search' => 'no'], '') }}" class="timer" style="width: 280px;" />
                <button type="submit" class="action_submit">提交</button>
            </div>
        </div>
    </form>
	<div style="overflow: auto; width: 100%;">
		<table class="gridtable" id="alternatecolor">
			<tr>
				<th>合同号</th>
				<th>库房</th>
				<th>类型</th>
				<th>产品数量</th>
				<th>出/入库时间</th>
				<th>操作者</th>
				<th>销售公司</th>
				<th>销售代表</th>
				<th>客户</th>
				<th>采购类别</th>
				<th>操作</th>
			</tr>
            @foreach ($recordsList as $records)
			<tr>
				<td>{{ ifValueEmpty($records->records['contract']) }}</td>
                <td>{{ $records->records->storehouse }}</td>
                <td class="genre">{{ \App\Models\BsRecords::$typeArr[$records->records->type] }}</td>
                <td>{{ $records->records->goods_no }}</td>
                <td>{{ dateFormat($records->records->created_at) }}</td>
                <td>{{ $records->records->operator }}</td>
				<td>{{ ifValueEmpty($records->records->company) }}</td>
				<td>{{ ifValueEmpty($records->records->seller) }}</td>
                <td>
					@if (!empty($records->records->client))
						{{ $records->records->client }}
						<i client="{{ $records->records->client }}" client_province="{{ $records->records->client_province }}" client_city="{{ $records->records->client_city }}" client_district="{{ $records->records->client_district }}">?</i>
					@else
						--
					@endif
				</td>
                <td>{{ !empty($records->records->purchase_type)?$records->records->purchase_type:'--' }}</td>
                <td>
                    <b record_id="{{ $records->records->id }}"
					   is_cancel="{{ $records->records->is_cancel }}"
					   type="{{ $records->records->type }}"
					   storehouse="{{ $records->records->storehouse }}"
					   contract="{{ $records->records->contract }}"
					   client="{{ $records->records->client }}（{{ $records->records->client_province }}-{{ $records->records->client_city }}-{{ $records->records->client_district }}）"
					   time_at="{{ dateFormat($records->records->created_at) }}"
					   operator="{{ $records->records->operator }}"
					   express_no="{{ $records->records->express_no }}"
					   purchase_type="{{ $records->records->purchase_type }}"
					   cancel_reason="{{ $records->records->cancel_reason }}"
					   goodsList="{{ json_encode($records->records->goods) }}"
					   remark="{{ $records->records->remark }}">
						查看详情
					</b>
                </td>
			</tr>
            @endforeach
		</table>
	</div>
	<!--分页-->
    {{ $recordsList->links() }}
</div>

<table id="wrapper_goods_hidden" hidden>
	<tbody>
		<tr class="oddrowcolor">
			<td id="deal_number"></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
</table>

<!--主体end-->
<!--弹出框start-->
	<!--客户-->
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
	<!--详情-->
	<div class="popUp detail">
		<div class="detailBox">
			<div class="detailBoxHeader">
				<i class="detailClose">×</i>
			</div>
			<div class="detailBoxMain">
				<div class="code">正常</div>
				<div class="gaugeOutfit">
					<h4><i></i>详情</h4>

					<div class="store">
						<div class="store_one">
							<span class="_wd294">库房：<i class="show_storehouse"></i></span>
							<span class="_wd280 edit">采购合同号：<i class="show_contract"></i></span>
							<span class="over overone hold" style="display: none;">保存</span>
							<span class="over overone make">修改</span>
							<input type="text" name="purchaseTypeName" value="" placeholder="请选择" readonly="readonly" class="simulation selected overinp seled">
							<label>采购类别：</label>
							<input type="text" name="purchaseTypeId" value="" hidden="">
							<input type="text" name="purchaseType" value="" hidden="">
							<ul class="check" style="display: none;">
								@foreach($purchaseTypeList as $purchaseTypeId => $purchaseTypeName)
	                            <li class="action_select_purchase" purchasetypeid="{{ $purchaseTypeId }}">{{ $purchaseTypeName }}</li>
								@endforeach
	                        </ul>
						</div>
						<p>
							<span class="_wd294">入库时间：<i class="show_in_at"></i></span>
							<span>操作者：<i class="show_operator"></i></span>
						</p>
						<p>
							<span class="show_name"><b>入库备注：</b><i class="show_remark"></i></span>
						</p>
						<p class="revokeCause">
							<span>撤销原因：<i class="show_cancel_reason"></i></span>
						</p>
					</div>

					<div class="deliver">
						<p>
							<span class="_wd184">库房：<i class="show_storehouse"></i></span>
							<span class="_wd278 edit">销售合同号：<i class="show_contract"></i></span>
							<span class="revise reviseone baocun" style="display: none;">保存</span>
							<span class="revise reviseone xiugai" hidden>修改</span>
							<span class="_wd278">客户：<i class="show_client"></i></span>
						</p>
						<p>
							<span class="_wd278">出库时间：<i class="show_out_at"></i></span>
							<span>操作者：<i class="show_operator"></i></span>
							<span class="awb tree">物流单号：<i class="show_express_no"></i></span>
							<span class="revise revisetwo baocun2" style="display: none;">保存</span>
							<span class="revise revisetwo xiugai2" hidden>修改</span>
						</p>
						<p><span class="show_name"><b>出库备注：</b><i class="show_remark"></i></span></p>
						<p class="revokeCause">
							<span>撤销原因：<i class="show_cancel_reason"></i></span>
						</p>
					</div>
				</div>
				<table class="gridtable pupupcolor" id="pupupcolor">
					<tr class="evenrowcolor">
						<th>货号</th>
						<th>型号</th>
						<th>品牌</th>
						<th>批号</th>
						<th>生产日期/有效期</th>
						<th>注册证号</th>
					</tr>

				</table>
				<div class="buttonOperate">
					<button class="config detailClose">确定</button>
					<button class="revoke">撤销入库</button>
					<button class="config printDealNumber" id="printDealNumber" hidden>批量打印货号</button>
				</div>
			</div>
		</div>
	</div>
	<!--撤销-->
	<div class="popUp revokeUp">
		<div class="popUpBox revokeUpBox">
			<h2>
				<span>撤销入库</span>
				<i class="revokeUpOff">×</i>
			</h2>
			<div class="revokeUpTest">
				<p id="cancel_record_type" hidden></p>
				<p id="cancel_record_id" hidden></p>
				<p id="cancel_record_text">请输入撤销入库原因</p>
				<textarea class="revokeUpText" id="cancel_reason_pop"></textarea>
				<button class="PopUpCloseRevoke">确定</button>
				<span class="revokeUpOff">取消</span>
			</div>
		</div>
	</div>

<script>
    var companyList = {!! json_encode($companyList) !!};
</script>
<!--弹出框end-->
@include('frontend.layouts.footer')
<script type="text/javascript" src="{{ URL('/common/plugins/distpicker/js/distpicker.js') }}"></script>

<script src="{{ URL('/frontend') }}/js/inOutRecords.js"></script>
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