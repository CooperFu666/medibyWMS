//下拉
function selsected(obj){
	var thisul = obj.parent().find("ul");
	if(thisul.css("display") == "none") {
		if(thisul.height() > 280) {
			thisul.css({
				height: "280" + "px"
			})
		}
		thisul.fadeIn("fast");
		thisul.hover(function() {}, function() {
			thisul.fadeOut("fast");
		});
		obj.parent().siblings('.termSub').find('ul').fadeOut("fast");
		thisul.find("li").click(function() {
			obj.val($(this).text());
			thisul.fadeOut("fast");
		});
	} else {
		thisul.fadeOut("fast");
	}
}
//下拉
$(".seled").click(function() {
	selsected($(this));
});
//省市区
// $.fn.citySelect();
//表格分色
function altRows(id){
	if(document.getElementsByTagName){
		var table = document.getElementById(id);
		var rows = table.getElementsByTagName("tr");
		for(i = 0; i < rows.length; i++){
			if(i % 2 == 0){
				rows[i].className = "evenrowcolor";
			}else{
				rows[i].className = "oddrowcolor";
			}
		}
	}
}
altRows('alternatecolor');
//显示客户
$(".gridtable tr td").find("i").click(function () {
	var obj = $(this);
    var client_province = obj.attr('client_province');
    var client_city = obj.attr('client_city');
    var client_district = obj.attr('client_district');
    $('.popUpBoxTest #client').text(obj.attr('client'));
    if (!isEmpty(client_province) && !isEmpty(client_city) && !isEmpty(client_district)) {
        $('.popUpBoxTest #address').text(client_province + '-' + client_city + '-' + client_district);
	}
    $(".client").fadeIn("fast");
});
//隐藏客户
$(".client").find(".PopUpClose").click(function () {
    $('.popUpBoxTest #client').text('');
    $('.popUpBoxTest #address').text('');
	$(".client").fadeOut("fast");
});

//出入库记录
$(".moldUl li").each(function(){
    $(this).click(function(){
    	let _html = $(this).html();
        let type = $(".mold").attr('data-type');
        $("input[name$='search[purchase_type]']").val('全部');
        $("#search_company").val('全部');
        $("#search_seller").val('全部');
        $("#province").val('全部');
        $("#city").val('全部');
        $("#district").val('全部');
        $("input[name$='search[client]']").val('全部');

        $(".mold").attr("data-type",_html);
        switchType(type);
    });
});
function switchType(type) {
    if(type == "入库"){
        $(".goodsIn input").removeAttr('disabled');
        $(".goodsIn").show();
        $(".downHide input").attr('disabled', 'disabled');
        $(".downHide").hide();
    }else if (type == "出库") {
        $(".goodsIn input").attr('disabled', 'disabled');
        $(".goodsIn").hide();
        $(".downHide input").removeAttr('disabled');
        $(".downHide").show();
        if ($("#search_company").val() == '全部') {
            $("#select_seller").hide();
        }
    }else if (type == "全部") {
        $(".goodsIn input").attr('disabled', 'disabled');
        $(".goodsIn").hide();
        $(".downHide input").attr('disabled', 'disabled');
        $(".downHide").hide();
    }
}
$(document).ready(function () {
    switchType(type);
});
//显示详情
$(".gridtable tr td").find("b").bind("click", function() {
    let record_id = $(this).attr('record_id');
    let record_type = $(this).attr('type');
    let is_cancel = $(this).attr('is_cancel');
    let storehouse = $(this).attr('storehouse');
    let contract = $(this).attr('contract');
    let client = $(this).attr('client');
    let time_at = $(this).attr('time_at');
    let operator = $(this).attr('operator');
    let express_no = $(this).attr('express_no');
    let purchase_type = $(this).attr('purchase_type');
    let cancel_reason = $(this).attr('cancel_reason');
    let remark = $(this).attr('remark');
    let goodsList = JSON.parse($(this).attr('goodsList'));
    $("input[name$='purchaseTypeName']").val(purchase_type);
    if (is_cancel != 1 && record_type == 2) {
		$(".xiugai,.xiugai2").show();
	} else {
        $(".xiugai,.xiugai2").hide();
    }
    if (record_type == 1) {
        $('.buttonOperate .revoke').text('撤销入库');
        $('#cancel_record_text').text('请输入撤销入库原因');
	} else {
        $('.buttonOperate .revoke').text('撤销出库');
        $('#cancel_record_text').text('请输入撤销出库原因');
    }
    if (is_cancel == 1) {
    	$('.code').text('已撤销').css("background","#f9b125");
    	$('.revokeCause').show();
    	$('.buttonOperate .revoke').hide();
    } else {
        $('.code').text('正常').css("background","#828bf9");
        $('.revokeCause').hide();
        $('.buttonOperate .revoke').show();
    }
    $("#cancel_record_type").text(record_type);
    $("#cancel_record_id").text(record_id);
    $('.show_cancel_reason').text(cancel_reason);
    $('.show_client').text(client);
    $('.show_contract').text(contract);
    $('.show_express_no').text(express_no);
    $('.show_in_at').text(time_at);
    $('.show_operator').text(operator);
    $('.show_out_at').text(time_at);
    $('.show_purchase_type').text(purchase_type);
    $('.show_remark').text(remark);
    $('.show_storehouse').text(storehouse);
    $(".pupupcolor .oddrowcolor").remove();
    for (let i = 0;i < goodsList.length; i++) {
        $("#wrapper_goods_hidden tbody tr td").eq(0).text(goodsList[i].deal_number);
        $("#wrapper_goods_hidden tbody tr td").eq(1).text(goodsList[i].version);
        $("#wrapper_goods_hidden tbody tr td").eq(2).text(goodsList[i].brand);
        $("#wrapper_goods_hidden tbody tr td").eq(3).text(goodsList[i].batch_no);
        $("#wrapper_goods_hidden tbody tr td").eq(4).text(dateFormatToDay(goodsList[i].valid_at));
        $("#wrapper_goods_hidden tbody tr td").eq(5).text(goodsList[i].reg_no);
		$('.pupupcolor tbody').append($("#wrapper_goods_hidden tbody").html());
    }
	$(".detail").fadeIn("fast");
	var genre = $(this).parents("tr").find(".genre").text();
	$(".gaugeOutfit h4").find("i").text(genre);
	var dataType = $(".gaugeOutfit h4").find("i").text();
	if(dataType == "入库"){
		$(".store").show();
		$(".deliver").hide();
		$("#printDealNumber").show();
	}else if(dataType == "出库"){
		$(".store").hide();
		$(".deliver").show();
        $("#printDealNumber").hide();
    }
});


//隐藏详情
$(".detail").find(".detailClose").bind("click", function() {
	$(".detail").fadeOut("fast");
	window.location.reload();
});
//撤销入库
$(".detail").find(".revoke").bind("click", function() {
	$(".detail").hide();
	$(".revokeUp").show();
});
//取消撤销入库
$(".revokeUp").find(".revokeUpOff").bind("click", function() {
	$(".revokeUp").fadeOut("fast");
});
//确定撤销入库
$(".revokeUp").find(".PopUpCloseRevoke").bind("click", function() {
    let record_id = $("#cancel_record_id").text();
    let url;
    let cancel_reason = $("#cancel_reason_pop").val();
	let jsonData = {_token:_token,cancel_reason:cancel_reason};
    if ($("#cancel_record_type").text() == 1) {
        url ='/home/cancelIn/'+record_id;
    }
	if ($("#cancel_record_type").text() == 2) {
        url ='/home/cancelOut/'+record_id;
    }
    $.ajax({
        url: url,
        type: "put",
        dataType: "json",
        data: jsonData,
        success: function(res) {
			if (res.code == 200) {
			    if (res.data.flag == 1) {
                    layer.msg("撤销入库成功！",{icon:1});
                    setTimeout(function () {
                        location.href = '/home/inOutRecords';
                    }, 1000);
                } else if (res.data.flag == 2) {
                    layer.msg("撤销入库失败！此次入库商品中已有商品出库！",{icon:2});
                } else {
                    layer.msg("撤销入库失败！",{icon:2});
                }
            } else {
                layer.msg("撤销入库失败！",{icon:2});
            }
        }, error: function(res) {
            layer.msg("请求失败！",{icon:2});
        }
    });
	// $(".revokeUp").fadeOut("fast");
	// $(".revokeCause").show();
	// var revokeUpText = $(this).parent(".revokeUpTest").find("textarea").val();
	// $(".revokeCause span").find("i").text(revokeUpText);
	// $(".code").css("background","#f9b125");
	// $(".code").text("已撤销");
});
//保存 
$(".deliver").find(".baocun").bind("click", function() {
    alterContract('sale');
});

$(".store").find(".hold").bind("click", function() {
    alterContract('purchase');
});

function alterContract(type) {
    let inp;
    let typeName;
    let typeInput;
    if (type === 'purchase') {
        inp = $('.overoneInput');
        typeName = '采购';
        typeInput = '.overoneInput';
    } else {
        inp = $('.reviseoneInput');
        typeName = '销售';
        typeInput = '.reviseoneInput';
    }
    if(inp.val().length > 25){
        return layer.tips(typeName + '合同号最长为25个字母或数字', typeInput);
    }
    if(inp.val() == ""){
        layer.tips('请输入'+typeName+'合同号!', typeInput);
    } else {
        let contract = inp.val();
        let record_id = $('#cancel_record_id').text();
        let jsonData = {_token:_token,contract:contract,record_id:record_id,type:type};
        $.ajax({
            url: '/home/alterContract',
            type: "put",
            dataType: "json",
            data: jsonData,
            success: function(res) {
                if (res.code == 200 && res.data.flag == 1) {
                    layer.msg("修改"+typeName+"合同成功！",{icon:1});
                    $(".show_contract").text(res.data.contract);
                } else {
                    layer.msg("修改"+typeName+"合同失败！",{icon:2});
                }
            }, error: function(res) {
                layer.msg("请求失败！",{icon:2});
            }
        });
        inp.parent().text(inp.val());
        inp.remove();
        $(".baocun").hide();
        $(".xiugai").show();
    }
}

$(".action_select_purchase").click(function () {
    let obj = $(this);
    let purchaseType = obj.text();
    let purchaseTypeId = obj.attr('purchaseTypeId');
    let recordId = $('#cancel_record_id').text();
    let jsonData = {_token:_token,record_id:recordId,purchase_type:purchaseType,purchase_type_id:purchaseTypeId};
    $.ajax({
        url: '/home/alterPurchaseType',
        type: "put",
        dataType: "json",
        data: jsonData,
        success: function(res) {
            if (res.code == 200 && res.data.flag == 1) {
                layer.msg("修改采购类型成功！",{icon:1});
            } else {
                layer.msg("修改采购类型失败！",{icon:2});
            }
        }, error: function(res) {
            layer.msg("请求失败！",{icon:2});
        }
    });
});

$(".deliver").find(".baocun2").bind("click", function () {
    alterExpressNo();
});

function alterExpressNo() {
    let inp = $('.revisetwoInput');
    if(inp.val().length > 25){
        return layer.tips('物流单号最长为25个字母或数字', '.revisetwoInput');
    }
    if(inp.val() == ""){
        layer.tips('请输入物流单号!', '.revisetwoInput');
    } else {
        let express_no = inp.val();
        let record_id = $('#cancel_record_id').text();
        let jsonData = {_token:_token,express_no:express_no,record_id:record_id};
        $.ajax({
            url: '/home/alterExpressNo',
            type: "put",
            dataType: "json",
            data: jsonData,
            success: function(res) {
                if (res.code == 200 && res.data.flag == 1) {
                    layer.msg("修改物流单号成功！",{icon:1});
                } else {
                    layer.msg("修改物流单号失败！",{icon:2});
                }
            }, error: function(res) {
                layer.msg("请求失败！",{icon:2});
            }
        });
        inp.parent().text(inp.val());
        inp.remove();
        $(".baocun2").hide();
        $(".xiugai2").show();
    }
}
//修改 
$(".deliver").find(".xiugai").bind("click", function() {
	var ed = $(this).parents(".deliver").find(".edit i");
	var html = "<input value='" + ed.text() + "' type='text' spellcheck='false' class='reviseoneInput'>";
	ed.html(html);
	$(".baocun").show();
	$(".xiugai").hide();
	ed.find('input').focus();
});
$(".deliver").find(".xiugai2").bind("click", function() {
	var ed = $(this).parents(".deliver").find(".tree i");
	var html = "<input value='" + ed.text() + "' type='text' spellcheck='false' class='revisetwoInput'>";
	ed.html(html);
	$(".baocun2").show();
	$(".xiugai2").hide();
	ed.find('input').focus();
});

$(".action_select_company").click(function () {
	var obj = $(this);
	var company_id = obj.attr('company_id');
    $("#search_company").val(company_id);
    if (obj.text() == '全部') {
        $("#search_seller").val('');
        $("#select_seller").hide();
    } else {
		var html = "<li>全部</li>";
		for (var i = 0;i < companyList.length ; i++) {
			if (companyList[i].id == company_id) {
				for (var j = 0; j < companyList[i].seller.length; j++) {
                    html += "<li seller_id='"+companyList[i].seller[j].id+"'>"+companyList[i].seller[j].name+"</li>";
				}
			}
		}
        $("#search_seller").val('全部');
        $("#select_seller ul").html(html);
        $("#select_seller").show();
    }
});

layui.use('laydate', function () {
    var laydate = layui.laydate;
    laydate.render({
        elem: '#search_time'
        ,range: '到'
    });
});

$(document).ready(function () {
    $.ajax({
        url: productLibraryUrl,
        type: "post",
        dataType: "json",
        data: {api:'library.brand',apiVersion:'v1',is_goods:true},
        success: function(res) {
            for (var i = 0; i < res.data.length; i++) {
                $("#brand_list").append("<li>"+res.data[i].title+"</li>");
            }
            refreshBind();
        }, error: function(res) {
            layer.msg("品牌列表请求失败！",{icon:2});
        }
    });
    // if ($("#search_company").val() == '全部') {
    //     $("#select_seller").hide().val('全部');
    // }
});

$("#export").mouseover(function () {
    $("input[name$='export']").removeAttr('disabled');
});

$("#export").mouseout(function () {
    $("input[name$='export']").attr('disabled', 'disabled');
});

var delay = 60000;
function refreshBind() {
    $(".action_ul_refresh li").click(function () {
        setTimeout(function () {
            $(".action_submit").click();
        }, 100);
    });
    $(".action_select_refresh").change(function () {
        delay = delay - 59900;
        setTimeout(function () {
            $(".action_submit").click();
        }, delay);
        delay = delay + 120000;
    });
}

$('body').on('blur','.reviseoneInput',function () {
    $(this).parent().parent().siblings('.baocun').click();
});
$('body').on('blur','.revisetwoInput',function () {
    $(this).parent().parent().siblings('.baocun2').click();
});

$("#printDealNumber").click(function () {
    let obj = $("#pupupcolor tbody tr #deal_number");
    let dealNumberList = [];
    if (obj.length > 1) {
        for (let i = 0 ;i < obj.length; i++) {
            dealNumberList[i] = obj.eq(i).text();
        }
    }
    $.ajax({
        url: '/home/generateDealNumberTxt',
        type: "put",
        dataType: "json",
        data: {_token:_token,dealNumberList:dealNumberList},
        success: function(res) {
            if (res.data.flag == 1) {
                location.href = '/home/downloadDealNumberTxt';
            }
        }, error: function(res) {
            layer.msg("请求失败！",{icon:2});
        }
    })
});

//新增
//入库详情
//保存采购合同号
$(".store").find(".hold").bind("click", function() {
	var inp = $('.overoneInput');
	inp.parent().text(inp.val());
	inp.remove();
	$(".hold").hide();
	$(".make").show();
});
//编辑采购合同号
$(".store").find(".make").bind("click", function() {
	var ed = $(this).parents(".store").find(".edit i");
	var html = "<input value='" + ed.text() + "' type='text' spellcheck='false' class='overoneInput'>";
	ed.html(html);
	$(".hold").show();
	$(".make").hide();
	ed.find('input').focus();
});
$('body').on('blur','.overoneInput',function () {
    $(this).parent().parent().siblings('.hold').click();
});