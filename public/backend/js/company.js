$(".action_seller_delete").on('click', function () {
    var sellerId = $(this).attr('seller_id');
    var sellerName = $(this).attr('seller_name');
    var url = "/admin/companySetting/sellerDestroy";
    var jsonData = {_token:LA.token,sellerId:sellerId};
    layer.confirm('是否删除销售代表（'+sellerName+'）？', {title:'提示',btn:['是', '否']}, function (index, layero) {
        $.ajax({
            url: url,
            type: "delete",
            dataType: "json",
            data: jsonData,
            success: function(res) {
                if (res.data.flag == 1) {
                    var obj = $(".action_seller_delete[seller_id='"+sellerId+"']").parent().parent();
                    obj.next().remove();
                    obj.remove();
                    layer.close(index);
                } else {
                    layer.msg('删除销售代表失败', {icon: 2});
                    layer.close(index);
                }
            },
            error: function (res) {
                layer.msg('删除销售代表失败', {icon: 2});
                layer.close(index);
            }
        });
    })
});

$(".action_company_delete").on('click', function () {
    var companyId = $(this).attr('company_id');
    var companyName = $(this).attr('company_name');
    var url = "/admin/companySetting/"+companyId+"/companyDestroy";
    var jsonData = {_token:LA.token};
    layer.confirm('是否删除公司（'+companyName+'）？', {title:'提示',btn:['是', '否']}, function (index, layero) {
        $.ajax({
            url: url,
            type: "delete",
            dataType: "json",
            data: jsonData,
            success: function(res) {
                if (res.data.flag == 1) {
                    location.reload();
                } else {
                    layer.msg('删除公司失败', {icon: 2});
                    layer.close(index);
                }
            },
            error: function (res) {
                layer.msg('删除公司失败', {icon: 2});
                layer.close(index);
            }
        });
    })
});

$("form").on('submit', function() {
    var sellerName = $("input[name$='sellerName']").val();
    var companyId = $("select[name$='companyId']").val();
    if (sellerName == '') {
        $("input[name$='sellerName']").focus();
        layer.msg('请输入销售员姓名！', {icon:2});
        return false;
    }
    // if (companyId == 0) {
    //     layer.msg('请选择公司！', {icon:2});
    //     return false;
    // }
});