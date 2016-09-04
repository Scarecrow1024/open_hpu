<?php
namespace Addons\TiCe\Controller;
use Home\Controller\AddonsController;

class TiCeController extends AddonsController{
    public function tice(){
        if(isset($cookie)){
            unset($cookie);
        }
        $openid=$_GET['openid'];
        //开启mem缓存
        /*$mmc = new \Think\Cache\Driver\Memcachesae();
        $ret = $mmc->connect();
        if(!$mmc->get($openid."tc")){*/
            $login_url="http://218.196.240.158/index.aspx";
            $ch=curl_init();
            curl_setopt($ch,CURLOPT_URL,$login_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch,CURLOPT_REFERER,"http://218.196.240.158/index.aspx");
            curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            $content=curl_exec($ch);
            curl_close($ch);
            $html=new SimpleHtmlController();
            $html->load($content);
            $__VIEWSTATE=$html->find('form')[0]->find('input',0)->value;
            $__VIEWSTATE=urlencode($__VIEWSTATE);
            $__EVENTVALIDATION=$html->find('form')[0]->find('input',1)->value;
            $__EVENTVALIDATION=urlencode($__EVENTVALIDATION);
            $user=M('user');
            $data = $user->where("openid=".'"'.$openid.'"')->find();
            //$studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
            //$IdCard = $user->where("openid=".'"'.$openid.'"')->getField('IdCard');
            $studentid=$data['studentid'];
            $IdCard=$data['IdCard'];
            $mm = substr($IdCard, 10, 8);
            $post="__VIEWSTATE=".$__VIEWSTATE."&__EVENTVALIDATION=".$__EVENTVALIDATION."&txtAccount=".$studentid."&txtPassword=".$mm."&rblUserType=Student&btnLogin=%E7%99%BB%E5%BD%95";

            $url="http://218.196.240.158/index.aspx";
            $ch=curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_HEADER,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
            //curl_setopt($ch,CURLOPT_COOKIEJAR, $cookiefile);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
            $content=curl_exec($ch);
            preg_match('/Set-Cookie:(.*);/iU',$content,$cookie); //正则匹配  
            curl_close($ch);

            $ch=curl_init();
            curl_setopt($ch,CURLOPT_URL,"http://218.196.240.158/welcome.aspx");
            curl_setopt($ch,CURLOPT_COOKIE, $cookie[1]);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            //curl_setopt($ch,CURLOPT_COOKIEJAR, $cookiefile);
            $content=curl_exec($ch);
            $arr=explode("=",$cookie[1]);
            curl_close($ch);
            unset($cookie);
            //$mmc->set($openid."tc", $content, false, 24*3600);
            $pattern = '/<td\s*>([\w\W]*?)<\/td>/';
            preg_match_all ( $pattern, $content, $out2, PREG_SET_ORDER );
            $pattern = '/<\/b>([\w\W]*?)<br \/>/';
            preg_match_all ( $pattern, $content, $out1, PREG_SET_ORDER );
            $pattern = "/总分([\w\W]*?)<\/span>/";
            preg_match_all ( $pattern, $content, $out3, PREG_SET_ORDER );
            $this->assign("out1",$out1);
            $this->assign("out2",$out2);
            $this->assign("out3",$out3);
            $this->display();
        /*}else{
            echo " ";
            $content=$mmc->get($openid."tc");  
            $pattern = '/<td\s*>([\w\W]*?)<\/td>/';
            preg_match_all ( $pattern, $content, $out2, PREG_SET_ORDER );
            $pattern = '/<\/b>([\w\W]*?)<br \/>/';
            preg_match_all ( $pattern, $content, $out1, PREG_SET_ORDER );
            $pattern = "/总分([\w\W]*?)<\/span>/";
            preg_match_all ( $pattern, $content, $out3, PREG_SET_ORDER );
            $this->assign("out1",$out1);
            $this->assign("out2",$out2);
            $this->assign("out3",$out3);
            $this->display();
        }    */   
    }
   
}
