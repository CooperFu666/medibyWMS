//下拉
function selsected(obj){
	var thisul = obj.parent().find("ul");
	if(thisul.css("display") == "none") {
		if(thisul.height() > 280) {
			thisul.css({
				"height": "280" + "px",
                "overflow": "auto"
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
    	var _html = $(this).html();
    	$(".mold").attr("data-type",_html);
    	var type = $(".mold").attr('data-type')
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
    }else if (type == "全部") {
        $(".goodsIn input").attr('disabled', 'disabled');
        $(".goodsIn").hide();
        $(".downHide input").attr('disabled', 'disabled');
        $(".downHide").hide();
    }
}
// $(document).ready(function () {
//     switchType(type);
// });
//显示详情
$(".gridtable tr td").find("b").bind("click", function() {
	$(".detail").fadeIn("fast");
	var genre = $(this).parents("tr").find(".genre").text();
	$(".gaugeOutfit h4").find("i").text(genre);
	var dataType = $(".gaugeOutfit h4").find("i").text();
	if(dataType == "入库"){
		$(".store").show();
		$(".deliver").hide();
	}else if(dataType == "出库"){
		$(".store").hide();
		$(".deliver").show();
	}
});
//隐藏详情
$(".detail").find(".detailClose").bind("click", function() {
	$(".detail").fadeOut("fast");
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
	$(".revokeUp").fadeOut("fast");
	$(".revokeCause").show();
	var revokeUpText = $(this).parent(".revokeUpTest").find("textarea").val();
	$(".revokeCause span").find("i").text(revokeUpText);
	$(".code").css("background","#f9b125");
	$(".code").text("已撤销");
});
//保存 
$(".deliver").find(".baocun").bind("click", function() {
	var inp = $(this).parents(".deliver").find(".edit i").find("input[type='text']").eq(0);
	if(inp.val() == ""){
		alert("请输入内容")
	}else{
		inp.parent().text(inp.val());
		inp.remove();
		$(".baocun").hide();
		$(".xiugai").show();
	}
});
$(".deliver").find(".baocun2").bind("click", function() {
	var inp = $(this).parents(".deliver").find(".tree i").find("input[type='text']").eq(0);
	if(inp.val() == ""){
		alert("请输入内容")
	}else{
		inp.parent().text(inp.val());
		inp.remove();
		$(".baocun2").hide();
		$(".xiugai2").show();
	}
});
//修改 
$(".deliver").find(".xiugai").bind("click", function() {
	var ed = $(this).parents(".deliver").find(".edit i");
	var html = "<input value='" + ed.text() + "' type='text' class='reviseoneInput'>";
	ed.html(html);
	$(".baocun").show();
	$(".xiugai").hide();
});
$(".deliver").find(".xiugai2").bind("click", function() {
	var ed = $(this).parents(".deliver").find(".tree i");
	var html = "<input value='" + ed.text() + "' type='text' class='revisetwoInput'>";
	ed.html(html);
	$(".baocun2").show();
	$(".xiugai2").hide();
});

$(".action_select_company").click(function () {
	var obj = $(this);
	var company_id = obj.attr('company_id');
    $("#search_company").val(company_id);
    if (obj.text() === '全部') {
        $("#search_seller").val('全部');
        $("#select_seller").hide();
    } else {
        $("#search_seller").val('全部');
        $("#select_seller").show();
	}
	let html = "<li>全部</li>";
	for (var i = 0;i < companyList.length ; i++) {
		if (companyList[i].id == company_id) {
			for (var j = 0; j < companyList[i].seller.length; j++) {
				html += "<li seller_id='"+companyList[i].seller[j].id+"'>"+companyList[i].seller[j].name+"</li>";
			}
		}
	}
	$("#select_seller ul").html(html);
});

$(".action_select_seller").click(function () {
	$("#search_seller_id").val($(this).attr('seller_id'));
});

$("#search_seller").click(function() {
    let obj = $(this);
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
            $("#search_seller_id").val($(this).attr('seller_id'));
            thisul.fadeOut("fast");
        });
    } else {
        thisul.fadeOut("fast");
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
            for (let i = 0; i < res.data.length; i++) {
                $("#brand_list").append("<li>"+res.data[i].title+"</li>");
            }
            refreshBind();
        }, error: function(res) {
            layer.msg("品牌列表请求失败！",{icon:2});
        }
    });
    if ($("#search_company").val() == '全部') {
		$("#select_seller").hide().val('全部');
	}
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