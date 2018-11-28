$('.closeAlert').click(function () {
    $('.changePsdBox').fadeOut()
})
$('#changePassword').click(function () {
    $('.changePsdBox').fadeIn()
})

$('.action_change_password_submit').click(function () {
    var oldPassword = $("input[name$='oldPassword']").val();
    var newPassword = $("input[name$='newPassword']").val();
    var newPasswordRepeat = $("input[name$='newPasswordRepeat']").val();
    var jsonData = {_token:_token,isAjax:1,oldPassword:oldPassword,newPassword:newPassword,newPasswordRepeat:newPasswordRepeat};
    var url = '/home/changePassword';
    if (oldPassword == '') {
        layer.msg('请输入原密码！', {icon:2});
        $("input[name$='oldPassword']").focus();
        return false;
    }
    if (newPassword == '') {
        layer.msg('请输入新密码！', {icon:2});
        $("input[name$='newPassword']").focus();
        return false;
    }
    if (newPasswordRepeat == '') {
        layer.msg('请再次输入新密码！', {icon:2});
        $("input[name$='newPasswordRepeat']").focus();
        return false;
    }
    if (newPassword != newPasswordRepeat) {
        layer.msg('新密码不相同！', {icon:2});
        return false;
    }
    $.ajax({
        url: url,
        type: "put",
        dataType: "json",
        data: jsonData,
        success: function(res) {
            if (res.data.flag == 1) {
                layer.msg('修改密码成功！', {icon:1});
                $('.changePsdBox').fadeOut();
                $("input[name$='oldPassword']").val('');
                $("input[name$='newPassword']").val('');
                $("input[name$='newPasswordRepeat']").val('');
            } else if (res.data.flag == 2) {
                layer.msg('原密码错误！', {icon: 2});
            } else {
                layer.msg('修改密码失败！', {icon: 2});
            }
        },
        error: function (res) {
            layer.msg('修改密码失败！', {icon: 2});
        }
    });
});