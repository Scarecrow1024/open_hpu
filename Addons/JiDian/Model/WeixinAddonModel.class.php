<?php

namespace Addons\JiDian\Model;

use Home\Model\WeixinModel;

/**
 * JiDian的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
        $url=addons_url ('JiDian://JiDian/login?openid='.get_openid());
        $dataArr[0]=array(
                'Title' => '绩点查询',
                'PicUrl' => 'http://us.51edu.com.au/sites/51edu.com.au/files/file/GPA.jpg',
                'Url' => $url
            );
        $user=M('user');
        $openid=get_openid();
        $url=addons_url ('Binding://Binding/login?openid='.$openid);
        $card = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        if($card==0){
        	$this->replyText('请绑定账号后使用,回复绑定');
        }else{
        	$this->replyNews($dataArr);
        }	
	}
	
	// 关注公众号事件
	public function subscribe() {
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
        	