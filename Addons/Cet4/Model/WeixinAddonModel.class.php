<?php

namespace Addons\Cet4\Model;

use Home\Model\WeixinModel;

/**
 * Cet4的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
                $url='http://cet.redrock-team.com/#';
                $dataArr[0]=array(
                'Title' => '四六级免准考证成绩查询',
                'PicUrl' => 'http://img1.imgtn.bdimg.com/it/u=3167187823,3740112694&fm=21&gp=0.jpg',
            	);
            	$dataArr[1]=array(
                'Title' => '官方|准考证查询入口',
                'PicUrl' => 'http://img3.imgtn.bdimg.com/it/u=1846745025,2752009162&fm=21&gp=0.jpg',
                'Url' => 'http://chaxun.neea.edu.cn/query/query_cet.html',
            	);

            	$dataArr[2]=array(
                'Title' => '准考证查询入口',
                'PicUrl' => 'http://img0.ph.126.net/-ByIDj-n0Lhwdia5x-sn_g==/6599309471842186911.jpg',
                'Url' => 'http://www.wxhand.com/addon/Cet46/Cet46View/query_view/token/e784d91275ab58dd91bff9f7ad5207d5.html'
            	);

                $dataArr[3]=array(
                'Title' => '免准考证查询入口②',
                'PicUrl' => 'http://img0.ph.126.net/-ByIDj-n0Lhwdia5x-sn_g==/6599309471842186911.jpg',
                'Url' => $url
            	);

            	$dataArr[4]=array(
                'Title' => '免准考证查询入口③',
                'PicUrl' => 'http://www.yunchafen.com.cn/score/alipay/cet-login'
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
        	