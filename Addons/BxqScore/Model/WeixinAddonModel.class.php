<?php

namespace Addons\BxqScore\Model;

use Home\Model\WeixinModel;

/**
 * BxqScore的微信模型
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
    		$openid=get_openid();
            $url1=addons_url ('BxqScore://BxqScore/login/openid/'.$openid);
            $url2=addons_url ('LinianScore://LinianScore/login/openid/'.$openid);
            $url3=addons_url ('TiCe://TiCe/tice/openid/'.$openid);
            $dataArr[0]=array(
            		'PicUrl' => 'http://hpuepaper.cuepa.cn/newspics/2014/02/s_da77199b1c4273443a288b10a017dcac206833.jpg',
                    'Title' => 'HPU小微成绩查询系统',
                );
            $dataArr[1]=array(
                    'Title' => "温馨提示：回复解绑然后绑定就可以导入下学期课表",
                );
            $dataArr[2]=array(
                    'Title' => "本学期成绩",
                    'PicUrl' => 'http://img5q.duitang.com/uploads/item/201506/15/20150615080039_xe43i.jpeg',
                    'Url' => $url1
                );
            $dataArr[3]=array(
                    'Title' => '历年成绩',
                    'PicUrl' => 'https://ss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=1372649611,3289850990&fm=21&gp=0.jpg',
                    'Url' => $url2
                );
            $dataArr[4]=array(
                    'Title' => '体育达标测试',
                    'PicUrl' => 'http://s9.sinaimg.cn/orignal/4b848eba1cd7e84d73148',
                    'Url' => $url3
                );
            $dataArr[5]=array(
                    'Title' => '四六级准考证查询',
                    'PicUrl' => 'http://img3.imgtn.bdimg.com/it/u=1846745025,2752009162&fm=21&gp=0.jpg',
                    'Url' => 'http://www.wxhand.com/addon/Cet46/Cet46View/query_view/token/e784d91275ab58dd91bff9f7ad5207d5.html'
                );

            $dataArr[6]=array(
                'Title' => '四六级免准考证查询',
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
        	