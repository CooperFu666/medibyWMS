<div class="changePsdBox" hidden>
    <div class="changePsd">
        <h3>修改密码<span class="closeAlert" style="position: relative;bottom: 24px">X</span></h3>
        <form onsubmit="return false;">
            <div class="form-body">
                <div class="form-group">
                    <label >原密码</label>
                    <div>
                        <input name="oldPassword" class="form-control" type="text"  placeholder="请输入密码">
                    </div>
                </div>
                <div class="form-group">
                    <label>新密码</label>
                    <div>
                        <input name="newPassword" class="form-control" type="password"  placeholder="请输入新密码">
                    </div>
                </div>
                <div class="form-group">
                    <label>重复新密码</label>
                    <div>
                        <input name="newPasswordRepeat" class="form-control" type="password"   placeholder="请再次输入新密码">
                    </div>

                </div>
                <div class="btn_box">
                    <button class="btn Determine action_change_password_submit" type="submit"><i class="fa fa-check"></i>确定</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="{{ URL('/common/plugins/layui2.4.3/layui.all.js')  }}"></script>
<script src="{{ URL('/common/js/jquery-3.2.1.js')  }}"></script>
<script src="{{ URL('/common/js/config.js')  }}"></script>
<script src="{{ URL('/common/js/common.js')  }}"></script>
<script src="{{ URL('/frontend/js/common.js')  }}"></script>
<script type="text/javascript" src="{{ URL('/frontend/js/changePassword.js') }}"></script>