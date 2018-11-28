@extends('frontend.login')
@section('content')
<link rel="stylesheet" href="{{ URL('/frontend') }}/css/login.css">

<div class="alertBox">
    <div class="alertContent">
        <h3>登录</h3>
        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <div class="form-body">
                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                    <label >账号</label>
                    <div>
                        <input placeholder="请输入账号" id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label>密码</label>
                    <div>
                        <input id="password" type="password" class="form-control" name="password" placeholder="请输入密码" required >
                        <div class="errorMessage">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                            @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>验证码</label>
                    <div class="verificationCode">
                        <input id="captcha"  class="form-control" type="captcha" name="captcha" value="{{ old('captcha')  }}" placeholder="请输入验证码" required>
                        <img src="{{captcha_src()}}" style="cursor: pointer" onclick="this.src='{{captcha_src()}}'+Math.random()">
                        <div class="errorMessage">
                            @if($errors->has('captcha'))
                                <div class="col-md-12">
                                    <p class="text-danger text-left"><strong>{{$errors->first('captcha')}}</strong></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="btn_box">
                    <button type="submit" class="btn Determine"><i class="fa fa-check"></i>登录</button>
                </div>
            </div>
        </form>
    </div>
    <div class="loginBanner">
        <img src="{{ URL('/frontend') }}/images/banner.png" alt="">
    </div>
</div>
@endsection
