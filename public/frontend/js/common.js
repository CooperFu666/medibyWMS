var _token = $("input[name$='_token']").val();

function isLogout() {
    event.preventDefault();
    layer.confirm('确认退出？', {title:'提示',btn:['确认', '取消']}, function (index, layero) {
        document.getElementById('logout-form').submit();
    });
}