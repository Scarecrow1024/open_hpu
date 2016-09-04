<?php

namespace Addons\Lib\Model;

use Home\Model\WeixinModel;

/**
 * Lib的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
        $url=addons_url ('Lib://Lib/grzx?openid='.get_openid());
        $dataArr[0]=array(
                'Title' => '河南理工大学图书馆',
                'Description' => '免账号登录,图书续借,借阅信息查询',
                'PicUrl' => 'http://imgsrc.baidu.com/forum/pic/item/42a98226cffc1e17c50a0b954a90f603728de9d9.jpg',
                'Url' => $url
            );
        $user=M('user');
        $openid=get_openid();
        $url=addons_url ('Binding://Binding/login?openid='.$openid);
        $card = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        if($card==0){
        	$this->replyText('绑定账号后免密码登录,回复绑定');
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
        	