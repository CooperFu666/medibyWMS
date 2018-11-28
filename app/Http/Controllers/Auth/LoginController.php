<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BsUsers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 使用用户名登录
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * 登录成功自定义跳转
     * @return \Illuminate\Http\RedirectResponse
     */
//    protected function redirectTo()
//    {
//        return redirect()->action('HomeController@index');
//    }

    /**
     * DESC: 重写 AuthenticatesUsers 登录验证方法，并自定义提示信息;
     * 原验证方法 Illuminate\Foundation\Auth\AuthenticatesUsers
     * @param Request $request
     */
    protected function validateLogin(Request $request){
        //  如果冻结则不能登录
        $data = $request->all();
        $user = BsUsers::where('username', $data['username'])->first();
        if (!empty($user) && ($user->is_freeze == BsUsers::FROZEN || $user->storehouse_id == 0)) {
            echo "<script>alert('该用户已被冻结！请联系管理员解除冻结！');history.back(-1)</script>";die;
        }

        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ],[
            'captcha.required' => '请填写验证码',
            'captcha.captcha' => '验证码错误',
        ]);
    }
}
