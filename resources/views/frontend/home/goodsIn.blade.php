@extends('frontend.base')
@section('content')
<link rel="stylesheet" href="{{ URL('/frontend/css/goodsInWarehouse.css') }}">
<div class="content">
    <!--头部-->
    <div></div>
    <!--内容-->
    <div class="contentMain">
        <div class="section">
            <h2>入库列表</h2>
            <div class="checkMenu">
                <div class="base">
                    <label>库房</label>
                    @foreach ($storehouseList as $storehouseId => $storehouseName)
                        <input type="text" name="storehouseName" placeholder="请选择" readonly="readonly" class="simulation selected" value="{{ $storehouseName }}">
                        <input type="text" name="storehouseId" value="{{ $storehouseId }}" hidden>
                    @endforeach
                    <ul class="check checkList action_select_storehouse">
                        @foreach ($storehouseList as $$storehouseId => $storehouseName)
                        <li storehouseId="{{ $storehouseId }}">{{ $storehouseName  }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="contract">
                    <label>采购合同号</label>
                    <input name="purchaseContractNo" type="text" placeholder="请输入合同号">
                </div>
                <div class="category">
                    <label>采购类别</label>
                    <input type="text" name="purchaseTypeName" value="" placeholder="请选择" readonly="readonly" class="simulation selected">
                    <input type="text" name="purchaseTypeId" value="" hidden>
                    <ul class="check checkList action_select_purchase_type">
                        @foreach ($purchaseTypeList as $typeId => $name)
                        <li purchaseTypeId="{{ $typeId }}">{{ $name  }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="Remarks">
                <label>入库备注</label>
                <textarea name="remark" rows="7" placeholder="请输入备注"></textarea>
            </div>
            <table class="goodsList">
                <tbody>
                    <tr>
                        <th class="action_select_checkbox_all" style="cursor: pointer;"><input type="checkbox" id="select_checkbox_all">型号</th>
                        <th>品牌</th>
                        <th>批号</th>
                        <th>生产日期/有效期</th>
                        <th>注册证号</th>
                        <th>货号</th>
                        <th>操作</th>
                    </tr>
                </tbody>
            </table>
            <div class="btnBox">
                <button class="btn goodsInWarehouse">+添加入库商品</button>
                <button class="btn copyPrevModel">+复制最后一个产品</button>
            </div>
        </div>

        <div class="submitBox">
            <div class="centerBox">
                <button class="btn DetermineInWarehousing" type="submit" id="sureGoodsIn">确定入库</button>
                <button class="btn batchPrinting" id="printDealNumber">批量打印货号</button>
                <button class="btn batchPrinting" id="del">删除所选产品</button>
            </div>
            {{--<p>统计：ABC-001 X10</p>--}}
        </div>
    </div>
</div>
<!--弹出框-->
<div class="alertBox">
    <div class="alertContent">
        <h3>商品入库 <span class="closeAlert">X</span></h3>
        <form class="layui-form">
            <div class="form-body">
                <div class="form-group">
                    <label >请输入型号</label>
                    <div>
                        <input class="form-control" type="text" id="version" name="version"  placeholder="请输入型号" lay-filter="version" lay-verify="required|version">
                    </div>
                </div>
                <div class="form-group">
                    <label>请输入数量</label>
                    <div>
                        <input class="form-control" type="text" id="no" name="no"  placeholder="请输入数量"  lay-filter="no" lay-verify="required|number|no">
                    </div>
                </div>
                <div class="form-group">
                    <label>批号</label>
                    <div>
                        <input class="form-control" type="text" id="batch_no" name="batch_no" placeholder="请输入批号"  lay-filter="batch_no" lay-verify="required|batch_no">
                    </div>
                </div>
                <div class="form-group">
                    <label style="line-height:22px;">生产日期/有效期</label>
                    <div>
                        <input class="form-control" type="text" id="valid_at" name="valid_at" placeholder="请输入生产日期/有效期"  lay-filter="valid_at" lay-verify="required|valid_at">
                    </div>
                </div>
                <div class="btn_box">
                    <button type="button" class="btn Determine" lay-submit lay-filter="add"><i class="fa fa-check"></i> 确定</button>
                    <button class="return" type="reset"><i class="fa fa-reply"></i> 取消</button>
                </div>
            </div>
        </form>
    </div>
</div>

<table id="tr_wrapper" hidden>
    <tbody>
        <tr class="goods">
            <td>
                <div>
                    <input type="checkbox" class="checkBox">
                    <input id="version" type="text"  class="write" onclick="setOldVersion($(this))" onblur="getInfoByVersion($(this))">
                </div>
            </td>
            <td id="brand"></td>
            <td>
                <input id="batch_no" type="text"  class="write">
            </td>
            <td>
                <input id="valid" class="valid write" type="text" readonly="readonly">
            </td>
            <td id="reg_no"></td>
            <td id="deal_number"></td>
            <td>
                {{--<a>打印货号</a>--}}
                <a class="delete">删除</a>
            </td>
        </tr>
    </tbody>
</table>

{{-- 包含页脚 --}}
@include('frontend.layouts.footer')
<script src="{{ URL('/frontend/js/goodsInWarehouse.js')  }}"></script>
@endsection