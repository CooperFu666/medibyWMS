$(".action_delete").on('click', function () {
    var url = "/admin/storehouseSetting/destroy";
    var storehouseId = $(this).attr('storehouse_id');
    var jsonData = {_token:LA.token,storehouseId: storehouseId};
    layer.confirm('是否删除库房？', {title:'提示',btn:['是', '否']}, function (index, layero) {
        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            data: jsonData,
            success: function(res) {
                if (res.data.flag == 1) {
                    location.reload();
                } else {
                    layer.msg('删除库房失败', {icon: 2});
                    layer.close(index);
                }
            },
            error: function (res) {
                layer.msg('删除库房失败', {icon: 2});
                layer.close(index);
            }
        });
    })
});

$(".action_is_freeze").on('click', function () {
    var url = "/admin/storehouseSetting/isFreeze";
    var isFreeze = $(this).attr('is_freeze');
    var userId = $(this).attr('user_id');
    var jsonData = {_token:LA.token,userId: userId,isFreeze:isFreeze};
    var content = '是否冻结？';
    if (isFreeze == FROZEN) {
        content = '是否解冻？';
    }
    layer.confirm(content, {title:'提示',btn:['是', '否']}, function (index, layero) {
        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            data: jsonData,
            success: function(res) {
                if (res.data.flag == 1) {
                    location.reload();
                } else {
                    layer.msg('冻结账号失败', {icon: 2});
                    layer.close(index);
                }
            },
            error: function (res) {
                layer.msg('冻结账号失败', {icon: 2});
                layer.close(index);
            }
        });
    })
});

$(".action_reset_password").on('click', function () {
    var url = "/admin/storehouseSetting/resetPassword";
    var userId = $(this).attr('user_id');
    var jsonData = {_token:LA.token,userId: userId};
    layer.confirm('是否重置密码？', {title:'提示',btn:['是', '否']}, function (index, layero) {
        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            data: jsonData,
            success: function(res) {
                if (res.data.flag == 1) {
                    location.reload();
                } else {
                    layer.msg('重置密码失败', {icon: 2});
                    layer.close(index);
                }
            },
            error: function (res) {
                layer.msg('重置密码失败', {icon: 2});
                layer.close(index);
            }
        });
    })
});