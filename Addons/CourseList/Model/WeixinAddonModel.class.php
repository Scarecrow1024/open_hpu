<?php

namespace Addons\CourseList\Model;

use Home\Model\WeixinModel;

/**
 * CourseList的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
		$openid=get_openid();
        $user=M('user');
        //判断是否绑定
        $studentid=$user->where("openid=".'"'.$openid.'"')->getField('studentid');
        if($studentid==0){
        	$this->subscribe();
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
        	$data=$user->where("openid=".'"'.$openid.'"')->getField('course');

	        $url=addons_url ('CourseList://CourseList/course?openid='.$openid);
	        $url2=addons_url ('Kclass://Kclass/login');

	        $data=json_decode($data,true);                   
	        $arr=array();
	        $weekarray=array("日","一","二","三","四","五","六");
	        if($weekarray[date("w")]=="六"||$weekarray[date("w")]=="日"){
	            $title="今天是星期".$weekarray[date("w")];
	            $arr[0]['Title']=$title;
	            $arr[0]['PicUrl']="http://imgsrc.baidu.com/forum/w%3D580/sign=b14afd2e0cf41bd5da53e8fc61da81a0/5c6409d162d9f2d39b783eeaabec8a136227ccde.jpg";
	            $arr[0]['Url']=$url;
	            $arr[1]['Title']="点我可查看空教室\n点击图片可查看全部课表";
	            $arr[1]['Url']=$url2;
	        }else{
	            $title="第四周星期".$weekarray[date("w")]."的课表如下";
	            $arr[0]['Title']=$title;
	            $arr[0]['Url']=$url;
	            $arr[0]['PicUrl']="http://imgsrc.baidu.com/forum/w%3D580/sign=b14afd2e0cf41bd5da53e8fc61da81a0/5c6409d162d9f2d39b783eeaabec8a136227ccde.jpg";
	            $arr[1]['Title']="温馨提示:回复解绑然后绑定可导入新学期课表\n点击图片可查看本周全部课表";
	            $arr[1]['Url']=$url;
	            foreach($data[date('w')] as $v){
	                $arr[]['Title']=$v;
	            }
	            
	        }

	        $this->replyNews($arr);
        }
        
		
	}
	
	// 关注公众号事件
	public function subscribe() {
		$user=M('user');
        $openid=get_openid();
        $data['openid']=$openid;
        $data['subscribe_time']=date("Y-m-d H:i",time());
        $is=$user->where("openid=".'"'.$openid.'"')->find();
        if(!$is){
            $user->add($data);
        }      
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
        	