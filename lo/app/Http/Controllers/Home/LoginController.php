<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use Hash;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\User;

use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

class LoginController extends Controller
{
    //引入登录页面
    public function getIndex()
    {
    	//引入登录页面
        return view('home.login');
    }

    //创建验证码
    public function create_code()
    {
    	$builder = new CaptchaBuilder;
		$builder->build($width = 125);
		header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        //将验证码存入session
        session(['code' => $builder->getPhrase()]);
        return $builder->output();
    }


    //执行登录
    public function postDologin(Request $request)
    {	
    	//接收登录数据
    	$user = $request -> input('user');
    	$pass = $request -> input('pass');
    	$code = $request -> input('code');

    	//查询手机号码是否存在
    	$res = User::where('phone',$user)->first();
    	if(!$res)
        {
    		$res = User::where('username',$user)->first();
    		if(!$res)
            {
    			echo 1; //用户名或手机号不存在
    			return;
    		}
    	}

        //匹配验证码
        $builder = new CaptchaBuilder;

        if(session('code') != $code)
        {
            echo 4; //验证码错误
            return;
        }
        
    	//匹配密码
    	if(Hash::check($pass,$res['password']))
        {
            // dd($res->userinfo->pic);
            session(['user' => $res['username']]);
    		session(['pic' => $res->userinfo['pic']]);
            $vtime = $res->userinfo['vip_time'] - time();
            if($vtime > 0)
            {
                session(['vip' => '6']); //是vip
                $res -> authority = 1;
                $res -> save();
            }else{
                session(['vip' => '4']); //不是vip
                $res -> authority = 0;
                $res -> save();
            }
    		echo 2; //登录成功
    		return;
    	}else{
    		echo 3; //密码错误
    		return;
    	}
    }


}
