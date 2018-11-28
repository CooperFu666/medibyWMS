// 关闭弹窗
$('.closeAlert,.return').click(function () {
    $('.alertBox').fadeOut()//关闭弹窗
});

$('body').on('click','.delete',function (){
    var that = $(this);
    layer.confirm('确认删除？', {title:'提示',btn:['确认', '取消']}, function (index, layero) {
        that.parents('.goodsList').find('.goods').length>1?$('#del').show().parent().css({'width':'708px'}):$('#del').hide().parent().css({'width':'478px'});
        that.parents('tr').remove();
        layer.close(index);
    })
});


// 下拉框获得焦点时
$('.selected').click(function () {
    var that = $(this);
    var text = $(this).siblings('.check');
    $(this).siblings('.checkList').fadeIn();
    $('.checkList li').unbind().click(function () {
        that.val($(this).text());
        that.siblings('.checkList').fadeOut();
    })
}).on('blur',function () {
    $(this).siblings('.checkList').fadeOut()
});

$("input[name$='purchaseTypeName']").click(function () {
    $('.checkList li').unbind().click(function () {
        var purchaseTypeId = $(this).attr('purchaseTypeId');
        var purchaseTypeName = $(this).text();
        $("input[name$='purchaseTypeId']").val(purchaseTypeId);
        $("input[name$='purchaseTypeName']").val(purchaseTypeName);
    });
});

$("input[name$='storehouseName']").click(function () {
    $('.checkList li').unbind().click(function () {
        var storehouseId = $(this).attr('storehouseId');
        var storehouseName = $(this).text();
        $("input[name$='storehouseId']").val(storehouseId);
        $("input[name$='storehouseName']").val(storehouseName);
    });
});


// 商品入库
$('.goodsInWarehouse').click(function () {
    $('.alertBox').toggle();
    layui.use('laydate', function () {
        var laydate = layui.laydate;
        laydate.render({
            elem: '#valid_at'
            ,trigger: 'click'
        });
    });
    $(".alertBox #version").val('');
    $(".alertBox #no").val('');
    $(".alertBox #batch_no").val('');
});

var goodsInfo;
layui.use('form', function(){
    var form = layui.form;
    form.verify({
        no: function(value, item){ //value：表单的值、item：表单的DOM对象
            if (value > 500) {
                return "数量不可以超过500！";
            }
        },
        batch_no: function(value, item){ //value：表单的值、item：表单的DOM对象
            if (value.length > 50) {
                return "批号太长，请重新输入！";
            }
        },
    });
    form.on('submit(add)', function(data){
        var url;
        data.field.version;    //当前容器的全部表单字段，名值对形式：{name: value}
        let how_much = parseInt($(".alertBox #no").val());
        let total = $(".goodsList tbody tr").length + how_much;
        if (total > 501) {
            layer.open({title:notice,content:"总入库数量不能超过500！", shadeClose:true});
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        }
        //  生成货号
        if ($(".goodsList tr").length > 1) {
            url = '/home/generateDealNumber/' + goodsInfo.brand_id + '/' + how_much + '/' + $(".goodsList tr:last #deal_number").text();
        } else {
            url = '/home/generateDealNumber/' + goodsInfo.brand_id + '/' + how_much + '/0';
        }
        $.ajax({
            url: url,
            type: "put",
            dataType: "json",
            data: {_token:_token},
            success: function(res) {
                how_much>=1?$('#del').show().parent().css({'width':'708px'}):$('#del').hide().parent().css({'width':'478px'});
                for (var i = 0; i < how_much; i++) {
                    $("#tr_wrapper #deal_number").text(res.data[i]);
                    $("#tr_wrapper #batch_no").attr('value', $("input[name='batch_no']").val());
                    $("#tr_wrapper #valid").attr('value', $("input[name='valid_at']").val());
                    let valid_at = $(".alertBox #valid_at").val();
                    var html = $("#tr_wrapper tbody").html();
                    $('.goodsList tbody').append(html);
                }
                layui.use('laydate', function () {
                    var laydate = layui.laydate;
                    lay('.valid').each(function(){
                        laydate.render({
                            elem: this
                            ,trigger: 'click'
                        });
                    });
                });
                $(document).click()
            }, error: function(res) {
                layer.msg("请求失败！",{icon:2});
            }
        });

        $('.alertBox').toggle();
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    });
});


$(".copyPrevModel").click(function () {
    if ($('.goodsList tbody tr').length > 1) {
        var html = '<tr class="goods">' + $('.goodsList tbody tr').last().html() + '</tr>';
        $('.goodsList tbody').append(html);
        let lastDealNumberObj = $('.goodsList tbody tr #deal_number').last();
        lastDealNumberObj.text(Number(lastDealNumberObj.text()) + 1);
        layui.use('laydate', function () {
            var laydate = layui.laydate;
            lay('.valid').each(function(){
                laydate.render({
                    elem: this
                    ,trigger: 'click'
                });
            });
        });
    } else {
        layer.msg('请先添加商品！', {icon:2});
    }
});

//获取指定位数的随机数
function getRandom(num){
    return Math.floor((Math.random()+Math.floor(Math.random()*9+1))*Math.pow(10,num-1));
}

//  获得型号
$("#version").blur(function () {
    var obj = $(this);
    var version = obj.val();
    $.ajax({
        url: productLibraryUrl,
        type: "post",
        dataType: "json",
        data: {api:'library.goods',apiVersion:'v1',version:version},
        success: function(res) {
            if (res.data.length <= 0) {
                $(".layui-form #version").attr('value', '');
                layer.open({title:notice,content:"暂无此型号！", shadeClose:true});
                obj.val('');
            } else {
                goodsInfo = res.data;
                $("#tr_wrapper #brand").text(res.data.brand);
                $("#tr_wrapper #version").attr('value', res.data.version);
                if (isEmpty(res.data.reg_num)) {
                    res.data.reg_num = '--';
                }
                $("#tr_wrapper #reg_no").text(res.data.reg_num);
            }
        }, error: function(res) {
            layer.msg("请求失败！",{icon:2});
        }
    })
});

var oldVersion;
function setOldVersion(obj) {
    this.oldVersion = obj.val();
}

function getInfoByVersion(obj)
{
    var version = obj.val();
    $.ajax({
        url: productLibraryUrl,
        type: "post",
        dataType: "json",
        data: {api:'library.goods',apiVersion:'v1',version:version},
        success: function(res) {
            if (res.data.length <= 0) {
                obj.val(oldVersion);
                layer.open({title:notice,content:"暂无此型号！", shadeClose:true});
            } else {
                obj.parent().parent().parent().children("#brand").text(res.data.brand);
                obj.parent().parent().parent().children("#version").attr('value', res.data.version);
                obj.parent().parent().parent().children("#reg_no").text(res.data.reg_num);
            }
        }, error: function(res) {
            layer.msg("请求失败！",{icon:2});
        }
    })
}


$("#del").click(function () {
    layer.confirm('', {
        btn: ['确认', '取消'],
        content: '是否删除已选中的产品？',
        shadeClose: true,
        yes: function () {
            $(".goodsList input[type='checkbox']:checked").not("#select_checkbox_all").parent().parent().parent().remove();
            $('.goodsList').find('.goods').length>0?$('#del').show().parent().css({'width':'708px'}):$('#del').hide().parent().css({'width':'478px'});
            $("#select_checkbox_all").prop("checked", false);
            layer.closeAll();
        }
    });
});




var arr= [];//shift多个选中
$(document).on("keydown keyup click", function(event){
//        ctrl17单选 shift116全选
    if(event.type == 'keyup'){
        return false
    }
    if(event.which == 16){
        $('.goodsList').find('.goods').unbind().click(function () {
            var star ;
            var end ;
            arr.push(parseInt($(this).index()-1));
            if(!$(this).find('input[type="checkbox"]').prop('checked')){
                $(this).find('input[type="checkbox"]').prop('checked','checked');
            }else {
                $(this).find('input[type="checkbox"]').prop('checked','');
            }
            // 设置循环的起始位置
            console.log(arr)
            if(arr[0] < arr[1]){
                end = arr[1];
                star = arr[0];
            }else {
                end = arr[0];
                star = arr[1];
            }
            if(arr.length >= 2){
                if($('.goods').eq(star).find('input[type="checkbox"]').prop('checked')){
                    for(var i = star;i <= end;i++){
                        $('.goods').eq(i).find('input[type="checkbox"]').prop('checked','checked');
                        $('.goods').eq(i).attr('checked','checked');
                    }

                }else {
                    for(var i = star;i <= end;i++){
                        $('.goods').eq(i).find('input[type="checkbox"]').prop('checked','');
                        $('.goods').eq(i).removeAttr('checked');
                    }
                }
                if(arr.length >=2){
                    arr.splice(0,2)
                }
            }
        })
    }
    if(event.type == 'click'){
        $('.goods').unbind().click(function () {
            arr.splice(0,1,parseInt($(this).index()-1));
            if(arr.length >=2){
                arr.splice(0,2)
            }
            $(this).find('input[type="checkbox"]').prop('checked')?$(this).find('input[type="checkbox"]').prop('checked',''):$(this).find('input[type="checkbox"]').prop('checked','checked')
            $(this).find('input[type="checkbox"]').prop('checked')?$(this).attr('checked','checked'):$(this).removeAttr('checked')
        });
        $('.goodsList').find('input').click(function (event) {
            event.stopPropagation();
        })
    }
});
$('body').on('change','#select_checkbox_all',function () {
    $(this).prop('checked')? $('.goods').find('input[type="checkbox"]').prop('checked',true): $('.goods').find('input[type="checkbox"]').prop('checked',false)
    $(this).prop('checked')? $('.goodsList').find('.goods').attr('checked','checked'): $('.goodsList').find('.goods').removeAttr('checked')
});
$('body').on('change','.checkBox',function () {
    $(this).prop('checked')? $(this).parents('.goods').attr('checked','checked'): $(this).parents('.goods').removeAttr('checked')
});




$("#printDealNumber").click(function () {
    let obj = $(".goodsList tbody tr #deal_number");
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

$("#sureGoodsIn").click(function () {
    let obj = $(".goodsList tbody tr");
    let goodsList = [];
    let records = {};
    if (obj.length > 1) {
        if (isEmpty($("input[name$='storehouseName']").val())) {
            return layer.tips('请选择库房', "input[name$='storehouseName']");
        }
        if (isEmpty($("input[name$='purchaseContractNo']").val())) {
            return layer.tips('请输入采购合同号', "input[name$='purchaseContractNo']");
        }
        if($("input[name$='purchaseContractNo']").val().length > 25) {
            return layer.tips('采购合同号最长为25个字母或数字', "input[name$='purchaseContractNo']");
        }
        if (isEmpty($("input[name$='purchaseTypeName']").val())) {
            return layer.tips('请选择采购类别', "input[name$='purchaseTypeName']");
        }
        // if (isEmpty($("textarea[name$='remark']").val())) {
        //     return layer.tips('请输入入库备注', "textarea[name$='remark']");
        // }
        if ($("textarea[name$='remark']").val().length > 255) {
            return layer.tips('备注最长为255个字母或数字', "#remark");
        }
        records.storehouse = $("input[name$='storehouseName']").val();
        records.storehouse_id = $("input[name$='storehouseId']").val();
        records.purchase_contract_no = $("input[name$='purchaseContractNo']").val();
        records.purchase_type = $("input[name$='purchaseTypeName']").val();
        records.purchase_type_id = $("input[name$='purchaseTypeId']").val();
        records.remark = $("textarea[name$='remark']").val();
        for (let i = 0; i < obj.length - 1 ; i++) {
            if (isEmpty($(".goodsList tbody tr #valid").eq(i).val())) {
                return layer.msg('请填写生产日期/有效期!', {icon:2});
            }
            let goods = {};
            goods.version = $(".goodsList tbody tr #version").eq(i).val();
            goods.brand = $(".goodsList tbody tr #brand").eq(i).text();
            goods.batch_no = $(".goodsList tbody tr #batch_no").eq(i).val();
            goods.valid_at = $(".goodsList tbody tr #valid").eq(i).val();
            goods.reg_no = !isEmpty($(".goodsList tbody tr #reg_no").eq(i).text()) ? $(".goodsList tbody tr #reg_no").eq(i).text() : 0;
            goods.deal_number = $(".goodsList tbody tr #deal_number").eq(i).text();
            goodsList[i] = goods;
        }
        if (!isEmpty(goodsList)) {
            let jsonData = {_token:_token,goodsList:goodsList,records:records};
            jsonData._token = _token;
            jsonData.goodsList = goodsList;
            $.ajax({
                url: '/home/goodsInSave',
                type: "put",
                dataType: "json",
                data: jsonData,
                success: function(res) {
                    if (res.data.flag == 1) {
                        layer.msg('入库成功！');
                        setTimeout(function () {
                            window.location = '/home/inOutRecords?search%5Btype%5D=入库';
                        }, 1000);
                    } else if ((res.data.flag == 2)) {
                        layer.msg('请勿重复提交！', {icon:2});
                    } else {
                        layer.msg('入库失败！', {icon:2});
                    }
                }, error: function(res) {
                    layer.msg("请求失败！",{icon:2});
                }
            })
        } else {
            layer.msg('请先选中商品！', {icon:2});
        }
    } else {
        layer.msg('请先添加商品！', {icon:2});
    }
});



