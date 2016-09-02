<?php

namespace Addons\Score\Model;

use Home\Model\WeixinModel;

/**
 * Score的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
		$openid=get_openid();
        $user=M('user');
        //判断是否绑定
        $studentid=$user->where("openid=".'"'.$openid.'"')->getField('studentid');
        if($studentid==0){
                $openid=get_openid();
                $url=addons_url ('Binding://Binding/login?openid='.$openid);
                $dataArr[0]=array(
                        'Title' => '绑定',
                        'Description' => '点击图片完成账号绑定',
                        'PicUrl' => 'http://www.pp3.cn/uploads/allimg/120129/1-120129232444.jpg',
                        'Url' => $url
                    );
                $dataArr[1]=array(
                        'Title' => '你还未绑定账号点击图片完成认证',                   
                    );
                        $this->replyNews($dataArr);
        }else{
		    $this->subscribe();
		    $openid=get_openid();
		    $url=addons_url ('Score://Score/login?openid='.$openid);
		    $dataArr[0]=array(
		            'Title' => '嗖HPU成绩查询系统',
		        );
		    $dataArr[1]=array(
                'Title' => "本学期成绩(暂时关闭)",
                'PicUrl' => 'http://img5q.duitang.com/uploads/item/201506/15/20150615080039_xe43i.jpeg',
            );
		    $dataArr[2]=array(
		            'Title' => '历年成绩查询',
		            'PicUrl' => 'http://img4.imgtn.bdimg.com/it/u=1492821203,583251059&fm=15&gp=0.jpg',
		            'Url' => $url
		        );
		    $url=addons_url ('TiCe://TiCe/tice?openid='.get_openid());
	        $dataArr[3]=array(
	                'Title' => '体育达标测试成绩查询',
	                'PicUrl' => 'http://ww3.sinaimg.cn/bmiddle/005AgsXujw1efv2hff546j315o0o6tje.jpg',
	                'Url' => $url
	            );
	        $dataArr[4]=array(
	                'Title' => '准考证查询入口',
	                'PicUrl' => 'http://img3.imgtn.bdimg.com/it/u=1846745025,2752009162&fm=21&gp=0.jpg',
	                'Url' => 'http://www.wxhand.com/addon/Cet46/Cet46View/query_view/token/e784d91275ab58dd91bff9f7ad5207d5.html'
            	);

            $dataArr[5]=array(
	            'Title' => '免准考证查询入口',
	            'PicUrl' => 'http://img0.ph.126.net/-ByIDj-n0Lhwdia5x-sn_g==/6599309471842186911.jpg',
	            'Url' => 'http://cet.redrock-team.com/#'
        	);


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
        	