<?php

namespace Addons\TiCe\Model;

use Home\Model\WeixinModel;

/**
 * TiCe的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
        $url=addons_url ('TiCe://TiCe/tice?openid='.get_openid());
        $dataArr[0]=array(
                'Title' => '体育达标测试成绩查询',
                'PicUrl' => 'http://ww3.sinaimg.cn/bmiddle/005AgsXujw1efv2hff546j315o0o6tje.jpg',
                'Url' => $url,
            );
        $user=M('user');
        $card = $user->where("openid=".'"'.get_openid().'"')->getField('studentid');
        if($card==0){
        	$this->replyText('该功能请绑定账号后使用,回复绑定');
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
        	