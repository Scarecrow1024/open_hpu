<?php

namespace Addons\Notice\Model;

use Home\Model\WeixinModel;

/**
 * Notice的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
        $this->subscribe();
        $openid=get_openid();
        $url=addons_url ('Notice://Notice/notice?openid='.$openid);
        $dataArr[0]=array(
                'Title' => '最新公告',
                'PicUrl' => 'http://ww2.sinaimg.cn/mw1024/005AgsXujw1f23n0d88zzj30c808ct9p.jpg',
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
        	