<?php

namespace Addons\LinianScore\Model;

use Home\Model\WeixinModel;

/**
 * LinianScore的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
        $url=addons_url ('LinianScore://LinianScore/login?openid='.get_openid());
        $dataArr[0]=array(
                'Title' => '历年成绩查询',
                'Description' => '点击图片查询历年成绩',
                'PicUrl' => 'http://ww2.sinaimg.cn/bmiddle/005AgsXujw1efv2hbkl17j31kw148k9v.jpg',
                'Url' => $url
            );
        $user=M('user');
        $openid=get_openid();
        $url=addons_url ('Binding://Binding/login?openid='.$openid);
        $card = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        if($card==0){
        	$this->replyText('绑定账号后查询历年成绩,回复绑定完成认证');
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
        	