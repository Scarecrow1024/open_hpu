<?php

namespace Addons\Repair\Model;

use Home\Model\WeixinModel;

/**
 * Repair的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() { 
		$url=addons_url ("Repair://Repair/index?openid=".get_openid());
		$dataArr[0]=array(
                'Title' => 'HPU小微报修',
                'PicUrl' => 'http://img1.imgtn.bdimg.com/it/u=2353317323,517820738&fm=21&gp=0.jpg',
                'Url' => $url
            );    
        $this->replyNews($dataArr);
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
        	