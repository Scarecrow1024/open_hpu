<?php

namespace Addons\Ubind\Controller;
use Home\Controller\AddonsController;

class UbindController extends AddonsController{
    //登录页面
    public function index(){  
        //$opeind=$_GET['openid'];
        $this->assign('openid',get_openid());
        $this->display();
    }

    //登录页面
    public function unbind(){ 
        if(isset($_POST['sure'])){
            $user=M('user');
            $openid=$_POST['openid'];
            $openid=get_openid();
            $a=$user->where("openid=".'"'.$openid.'"')->setField('studentid',0);
            if($a){
                $this->success("解绑成功,等待跳转后就可绑定其它账号",U('/addon/Binding/Binding/login/'),2);
            }else{
                $this->subscribe();
                $this->success("解绑成功,等待跳转后就可绑定其它账号",U('/addon/Binding/Binding/login/'),2);
            }
        }
    }

    // 关注公众号事件
    public function subscribe() {
        $user=D('user');
        $openid=get_openid();
        $data=array();
        $data['openid']=$openid;
        $data['subscribe_time']=date("Y-m-d H:i",time());
        $is=$user->where("openid=".'"'.$openid.'"')->find();
        if($is==NULL){
            $user->add($data);
        }      
        return true;
    }
}
