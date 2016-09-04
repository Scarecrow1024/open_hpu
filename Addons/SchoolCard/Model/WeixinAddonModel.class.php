<?php

namespace Addons\SchoolCard\Model;

use Home\Model\WeixinModel;

/**
 * SchoolCard的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
        $user=M('user');
        $openid=get_openid();
        $url=addons_url ('Binding://Binding/login?openid='.$openid);
        $card = $user->where("openid=".'"'.$openid.'"')->getField('IdCard');
        if($card==null){
                $this->replyText('请绑定账号后使用,回复绑定');
        }else{
                $rs=curl_init();
                $user=M('user');
                $openid=get_openid();
                //$studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
                $data = $user->where("openid=".'"'.$openid.'"')->find();
                //$idcard = $user->where("openid=".'"'.$openid.'"')->getField('IdCard');
                $idcard=$data['IdCard'];
                $studentid=$data['studentid'];
                $idcard=substr($idcard, 11, 6);
                //post提交
                $url="http://my.hpu.edu.cn/userPasswordValidate.portal";
                $post="Login.Token1=".$studentid."&Login.Token2=".$idcard."&goto=http%3A%2F%2Fmy.hpu.edu.cn%2FloginSuccess.portal&gotoOnFail=http%3A%2F%2Fmy.hpu.edu.cn%2FloginFailure.portal"; 
                curl_setopt($rs,CURLOPT_URL,$url);
                //post数据来源
                curl_setopt($rs,CURLOPT_REFERER,"http://my.hpu.edu.cn/login.portal");
                curl_setopt($rs,CURLOPT_POST,1);
                curl_setopt($rs,CURLOPT_POSTFIELDS,$post);
                //设置cookie
                curl_setopt($rs,CURLOPT_COOKIESESSION,1);
                curl_setopt($rs,CURLOPT_COOKIEFILE,"cookie");
                curl_setopt($rs,CURLOPT_COOKIEJAR, "cookie");
                curl_setopt($rs,CURLOPT_COOKIE,session_name().'='.session_id());
                curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
                curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
                //跳转到数据页面
                curl_exec($rs);
                curl_setopt($rs,CURLOPT_URL,"http://my.hpu.edu.cn/pnull.portal?.p=Znxjb20ud2lzY29tLnBvcnRhbC5zaXRlLnYyLmltcGwuRnJhZ21lbnRXaW5kb3d8ZjEyMDd8dmlld3xub3JtYWx8&.nctp=true&.ll=true&.reqId=454292a2-805e-11e5-8ee7-93b0a51465ca&.isTW=false");
                curl_setopt($rs,CURLOPT_REFERER,"http://my.hpu.edu.cn/index.portal");
                curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
                $content=curl_exec($rs);
                curl_close($rs);
                //echo $content;
                //$html = new simple_html_dom();
                $content=strip_tags($content)."<br>";
                preg_match_all("/[0-9]+/", $content, $matches);
                //echo "<pre>";
                //print_r($matches);
                $str="你的饭卡余额为￥".$matches[0][4].".".$matches[0][5]."元"."\n温馨提示:你有".$matches[0][1]."项赛课作业";
                $user=M('user');
                $openid=get_openid();
                $url=addons_url ('Binding://Binding/login?openid='.$openid);
                $card = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
                if($card==0){
                        $this->replyText('绑定账号后查询饭卡余额,回复绑定');
                }else{
                        $this->replyText($str);
                }
        
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
        	