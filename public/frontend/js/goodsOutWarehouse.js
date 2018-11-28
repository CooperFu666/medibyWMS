var time;
var dealNumberList = [];
var deal_number_scanning = '';

var addCompany;
var addProvince;
var addCity;
var addDistrict;
var addProvinceId;
var addCityId;
var addDistrictId;
// 下拉框获得焦点时
$('.selected').click(function () {
    var that = $(this);
    var width =$(this).css('width');
    $(this).siblings('.check').css({'width':"188px"});
    $(this).siblings('.checkList').fadeIn();
    if(that.siblings('.checkList').height() > 280) {
        that.siblings('.checkList').css({
            "overflow": "auto",
            "height":"280px"
        })
    }
    $('.checkList li').unbind().click(function () {
        that.val($(this).text());
        that.siblings('.checkList').fadeOut();
    })
}).bind('blur',function () {
    $(this).siblings('.checkList').fadeOut()
});

$("#company").click(function () {
    $('.checkList li').unbind().click(function () {
        var companyId = $(this).attr('companyId');
        var client = $(this).text();
        $("#seller").val('');
        $(".category ul li").remove();
        $("#company").val(client);
        $("#company_id").val(companyId);
        for (let i = 0; i < companyList.length; i++) {
            if (companyId == companyList[i].id) {
                for (let j = 0; j < companyList[i].seller.length; j++) {
                    let html = '<li sellerId="'+companyList[i].seller[j].id+'">'+companyList[i].seller[j].name+'</li>';
                    $(".category ul").append(html);
                }
            }
        }
    });
});

$("#seller").click(function () {
    $('.checkList li').unbind().click(function () {
        var sellerId = $(this).attr('sellerId');
        var seller = $(this).text();
        $("#seller").val(seller);
        $("#seller_id").val(sellerId);
    });
});

$("#client").click(function () {
    $('.checkList li').unbind().click(function () {
        var clientId = $(this).attr('clientId');
        var client = $(this).text();
        $("#client").val(client);
        $("#client_id").val(clientId);
    });
});

$("#client").blur(function () {
    let obj = $(this);
    addCompany = obj.val();
    $.ajax({
        url: '/home/isExistClient/'+obj.val(),
        type: "put",
        dataType: "json",
        data: {_token:_token},
        success: function(res) {
            if (res.code == 200) {
                if (res.data.flag == 0) {
                    $(".action_is_add_company").text(obj.val());
                    $(".isAdd").fadeIn();
                }
            } else {
                layer.msg("请求失败！错误码:"+res.code,{icon:2});
            }
        }, error: function(res) {
            console.log("请求失败！");
        }
    });
});

function bindDel() {
    $(".delete").click(function () {
        let obj = $(this)
        layer.confirm('确认删除？', {title:'提示',btn:['确认', '取消']}, function (index, layero) {
            obj.parents('tr').remove();
            layer.close(index);
            dealNumberList.remove(Number(obj.parents().find('#deal_number_hidden').text()));
        })
    });
}

// 关闭弹窗
$('.closeAlert,.return,.successBtn').click(function () {
    $('.alertBox').fadeOut()//关闭弹窗
})

$('.determineAdd').click(function () {
    $(this).parents('.alertBox').fadeOut()
    $('.customerAddressBox').fadeIn()
});

function selsected(obj){
    var width = $(obj).css("width")
    console.log(width)
    var thisul = obj.parent().find("ul");
    if(thisul.css("display") == "none") {
        if(thisul.height() > 280) {
            thisul.css({
                "height": "280" + "px",
                "overflow": "auto",
                "width":width
            })
        };
        thisul.fadeIn("fast");
        thisul.hover(function() {}, function() {
            thisul.fadeOut("fast");
        })
        thisul.find("li").click(function() {
            obj.val($(this).text());
            thisul.fadeOut("fast");
        });
    } else {
        thisul.fadeOut("fast");
    }
}

$(".brand,.state,.firm,.sales,.time").click(function() {
    selsected($(this));
});

$("body").keydown(function (event) {
    time = +new Date();
});
$("body").keyup(function(event) {
    let dTime= +new Date() - time;
    if (dTime <= 15 && event.keyCode >= 48 && event.keyCode <= 57) {  //扫码仪输入
        deal_number_scanning += switchValue(event.keyCode);
    }
    if (event.keyCode === 13) {
        let focusId = document.activeElement.id;
        if (!isEmpty(focusId)) {
            if ($('#'+focusId).is('input')) {
                $('#'+focusId).val($('#'+focusId).val().replace(deal_number_scanning, ''));
            }
            if ($('#'+focusId).is('textarea')) {
                $('#'+focusId).val($('#'+focusId).val().replace(deal_number_scanning+'\n', ''));
            }
        }
        if ($.inArray(Number(deal_number_scanning), dealNumberList) === -1) {
            dealNumberList.push(Number(deal_number_scanning));
            $.ajax({
                url: '/home/getGoodsInfo/'+Number(deal_number_scanning),
                type: "get",
                dataType: "json",
                success: function(res) {
                    if (res.code == 200 && res.data.flag == 1) {
                        if (!isEmpty(res.data.goods)) {
                            $("#tr_wrapper #deal_number_hidden").text(res.data.goods.deal_number);
                            $("#tr_wrapper #brand_hidden").text(res.data.goods.brand);
                            $("#tr_wrapper #batch_no_hidden").text(res.data.goods.batch_no);
                            $("#tr_wrapper #valid_at_hidden").text(res.data.goods.valid_at);
                            $("#tr_wrapper #reg_no_hidden").text(res.data.goods.reg_no);
                            $("#tr_wrapper #purchase_contract_no_hidden").text(res.data.goods.purchase_contract_no);
                            $(".goodsList tbody").append($('#tr_wrapper tbody').html());
                            layui.use('laydate', function () {
                                var laydate = layui.laydate;
                                lay('.goodsList .valid_at_hidden').each(function(){
                                    laydate.render({
                                        elem: this
                                        ,trigger: 'click'
                                    });
                                });
                            });
                            bindDel();
                            window.scrollTo(0, document.documentElement.clientHeight);
                        } else {
                            layer.msg("暂无此商品数据!",{icon:2});
                        }
                    } else if (res.data.flag == 0) {
                        layer.msg("暂无此商品数据！",{icon:2});
                    } else if (res.data.flag == 2) {
                        layer.msg("此商品已出库",{icon:2});
                    } else if (res.data.flag == 3) {
                        layer.msg("商品已撤销入库！",{icon:2});
                    }
                    else {
                        layer.msg("请求失败！错误码:"+res.code,{icon:2});
                    }
                }, error: function(res) {
                    layer.msg("请求失败！",{icon:2});
                }
            });
        } else {
            layer.msg("该商品已扫描！",{icon:2});
        }
        deal_number_scanning = '';
    }
});

function switchValue(keyCode) {
    let deal_number;
    switch(keyCode) {
        case 48:
            deal_number = 0;
            break;
        case 49:
            deal_number = 1;
            break;
        case 50:
            deal_number = 2;
            break;
        case 51:
            deal_number = 3;
            break;
        case 52:
            deal_number = 4;
            break;
        case 53:
            deal_number = 5;
            break;
        case 54:
            deal_number = 6;
            break;
        case 55:
            deal_number = 7;
            break;
        case 56:
            deal_number = 8;
            break;
        case 57:
            deal_number = 9;
            break;
    }
    return deal_number.toString();
}

$(".DetermineInWarehousing").click(function () {
    let record = {};
    let deal_number_list = [];
    let sale_contract_no = $("#sale_contract_no").val();
    let company = $("#company").val();
    let company_id = $("#company_id").val();
    let client = $("#client").val();
    let client_id = $("#client_id").val();
    let seller = $("#seller").val();
    let seller_id = $("#seller_id").val();
    let express_no = $("#express_no").val();
    let remark = $("#remark").val();
    // if (isEmpty(sale_contract_no)) {
    //     layer.tips('请输入销售合同号！', '#sale_contract_no');
    //     return false;
    // }
    // if (isEmpty(express_no)) {
    //     layer.tips('请输入物流单号！', '#express_no');
    //     return false;
    // }
    if(sale_contract_no.length > 25) {
        return layer.tips('销售合同号最长为25个字母或数字', "#sale_contract_no");
    }
    if(express_no.length > 25) {
        return layer.tips('物流单号最长为25个字母或数字', "#express_no");
    }
    if (isEmpty(company)) {
        layer.tips('请选择公司！', '#company');
        return false;
    }
    if (isEmpty(seller)) {
        layer.tips('请选择销售代表！', '#seller');
        return false;
    }
    if (isEmpty(client)) {
        layer.tips('请选择客户！', '#client');
        return false;
    }
    // if (isEmpty(remark)) {
    //     layer.tips('请输入出库备注！', '#remark');
    //     return false;
    // }
    if (remark.length > 255) {
        return layer.tips('备注最长为255个字母或数字', "#remark");
    }
    if ($(".goodsList tbody tr").length <= 1) {
        layer.msg('请先扫描出库商品！', {icon:2});
        return false;
    }
    record.sale_contract_no = sale_contract_no;
    record.company = company;
    record.company_id = company_id;
    record.client = client;
    record.client_id = client_id;
    record.seller = seller;
    record.seller_id = seller_id;
    record.express_no = express_no;
    record.remark = remark;
    for (let i = 0; i < $(".goodsList tbody tr").length - 1; i++) {
        deal_number_list.push($(".goodsList #deal_number_hidden").eq(i).text());
    }
    let jsonData = {_token:_token,dealNumberList:deal_number_list,record:record};
    $.ajax({
        url: '/home/goodsOutSave',
        type: "put",
        dataType: "json",
        data: jsonData,
        success: function(res) {
            if (res.data.flag == 1) {
                layer.msg('出库成功！');
                setTimeout(function () {
                    window.location = '/home/inOutRecords?search%5Btype%5D=出库';
                }, 1000);
            } else if (res.data.flag == 2) {
                layer.msg('请不要重复提交！', {icon:2});
            } else {
                layer.msg('出库失败！', {icon:2});
            }
        }, error: function(res) {
            layer.msg("请求失败！",{icon:2});
        }
    })
});

//地址
$("#province").on('change', function () {
    addProvince = $("#province option:selected").attr('data-text');
    addProvinceId = $("#province option:selected").attr('data-code');
});
$("#city").on('change', function () {
    addCity = $("#city option:selected").attr('data-text');
    addCityId = $("#city option:selected").attr('data-code');
});
$("#district").on('change', function () {
    addDistrict = $("#district option:selected").attr('data-text');
    addDistrictId = $("#district option:selected").attr('data-code');
});

$(function () {
    $('#distPicker').distpicker({
        province: '请选择省份',
        city: '请选择城市',
        district: '请选择地区',
        autoSelect: false,
    });
});

// 选择地址后提交给后台
$('.determineAddress').click(function () {
    let obj = $(this);
    let jsonData = {_token:_token,addCompany:addCompany,addProvince:addProvince,addCity:addCity,addDistrict:addDistrict,addProvinceId:addProvinceId,addCityId:addCityId,addDistrictId:addDistrictId};
    $.ajax({
        url: '/home/addClient',
        type: "put",
        dataType: "json",
        data: jsonData,
        success: function(res) {
           if(res.data.flag == 1){
               let dom = '<li clientId="'+res.data.clientId+'">'+addCompany+'</li>';
               $(".action_is_add_address").text(addProvince + '-' + addCity + '-' + addDistrict);
               $(".action_select_client").append(dom);
               $("#client_id").val(res.data.clientId);
               obj.parents('.alertBox').fadeOut();//关闭弹窗
               $('.successAdd').fadeIn();
           } else {
               layer.msg('添加失败！', {icon:2});
           }
        }, error: function(res) {
            console.log('请求失败！');
        }
    })
});