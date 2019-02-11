<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Smx\Wx\Robot;
use Illuminate\Http\Request;

class RobotController extends Controller {
    public function cookies(Request $request)
    {
        $wechat = new Robot();
        //获取用户uin 和 sid
        $url = $request->input('url');
        $wxinfo = $wechat->getCookies($url);
        $wxinfo['status'] = 1;

        return json_encode($wxinfo);
    }

    public function status(Request $request)
    {
        $wechat = new Robot();
        //获取登录状态
        $uuid = $request->input('uuid');
        $res = $wechat->getLoginStatus($uuid);
        if($res == 201){
            //已扫描，待确认
            $data = array('status' => 1);
        }elseif (substr_count($res, 'http')) {
            //确认成功
            $data = array('status' => 2);
        }else{
            //待扫描
            $data = array('status' => 0);
        }
        $data['msg'] = $res;
        return json_encode($data);
    }
    public function chat(Request $request)
    {
        //主聊天框页面
        return view('chat');
    }

    public function init(Request $request)
    {
        $wechat = new Robot();
        //初使化微信信息
        $json_info = $wechat->initWebchat();
        return $json_info;
    }
    public function users(Request $request)
    {
        $wechat = new Robot();
        //获取所有好友列表
        $users = $wechat->getContact();
        echo $users;
    }
    public function send(Request $request)
    {
        $wechat = new Robot();

        $toUsername = $request->input('toUsername');
        $content = $request->input('content');

        $res = $wechat->sendMessage($toUsername, $content);
        return $res;
    }

    public function sync(Request $request)
    {
        $wechat = new Robot();
        //服务器同步
        $synckey = $request->input('synckey');
        $message = $wechat->wxsync($synckey);

        return $message;
    }

    public function avatar(Request $request)
    {
        $wechat = new Robot();

        $uri = $request->input('uri');
        $res = $wechat->getAvatar($uri);
        header('Content-Type: image/jpeg');
        imagejpeg($res);
    }
    public function tuling(Request $request)
    {
        $wechat = new Robot();

        //图灵机器人接管消息
        $toUsername = $request->input('toUsername');
        $content = $request->input('content');
        if($toUsername != session('username')){
            $mes = $wechat->sendMessageToTuling($content);
            $res = $wechat->sendMessage($toUsername, $mes);
            //拼接上机器人的回话
            $tlCon = json_decode($res,true);
            $tlCon['tlc'] = $mes;
            $tlCon['status'] = 1;
            return json_encode($tlCon);
        }
        return json_encode(array('status' => 0));
    }

    public function index(Request $request) {
        $wechat = new Robot();
        $act = $request->input('act', 'index');

        //登录页
        $uuid = $wechat->getUuid();
        $qrcode = "https://login.weixin.qq.com/qrcode/{$uuid}?t=webwx";
        return view('qrcode', ['qrcode' => $qrcode, 'uuid' => $uuid]);
    }
}

