<?php

namespace Addons\Kclass\Model;

use Home\Model\WeixinModel;

/**
 * Kclass的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
        $openid=get_openid();
        $url=addons_url ('Kclass://Kclass/login?openid='.$openid);
        $dataArr[0]=array(
                'Title' => '空教室查询',
                'PicUrl' => 'http://hpuepaper.cuepa.cn/newspics/2014/02/s_da77199b1c4273443a288b10a017dcac206833.jpg',
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
        	