<?php

namespace Addons\Ubind\Model;

use Home\Model\WeixinModel;

/**
 * Ubind的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
		$this->subscribe();
        $url=addons_url ('Ubind://Ubind/index?openid='.get_openid());
        $dataArr[0]=array(
                'Title' => '解除绑定',
                'PicUrl' => 'http://www.pp3.cn/uploads/allimg/120129/1-120129232444.jpg',
                'Url' => $url
            );
		$this->replyNews($dataArr);
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
	
	// 取消关注公众号事件
	public function unsubscribe() {
		return true;
	}
	
	// 扫描带参数二维码事件
	public function scan() {
		return true;
	}
	
	// 上报地理位置事件
	public function location() {
		return true;
	}
	
	// 自定义菜单事件
	public function click() {
		return true;
	}
}
        	