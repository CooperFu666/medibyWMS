$(".action_purchase_type_delete").on('click', function () {
    var purchaseTypeId = $(this).attr('purchaseTypeId');
    var url = "/admin/purchaseTypeSetting/"+purchaseTypeId+"/destroy";
    var purchaseTypeName = $(this).attr('purchaseTypeName');
    var jsonData = {_token:LA.token,purchaseTypeId: purchaseTypeId,purchaseTypeName:purchaseTypeName};
    layer.confirm('是否删除采购类型（'+purchaseTypeName+'）？', {title:'提示',btn:['是', '否']}, function (index, layero) {
        $.ajax({
            url: url,
            type: "delete",
            dataType: "json",
            data: jsonData,
            success: function(res) {
                if (res.data.flag == 1) {
                    var obj = $(".action_purchase_type_delete[purchasetypeid='"+purchaseTypeId+"']").parent().parent();
                    obj.next().remove();
                    obj.remove();
                    layer.close(index);
                } else {
                    layer.msg('删除采购类型（'+purchaseTypeName+'）失败', {icon: 2});
                    layer.close(index);
                }
            },
            error: function (res) {
                layer.msg('删除采购类型（'+purchaseTypeName+'）失败', {icon: 2});
                layer.close(index);
            }
        });
    })
});