<?php

namespace Addons\LinianScore\Controller;
use Home\Controller\AddonsController;

class LinianScoreController extends AddonsController{
    //登录vpn获取验证码并且保存cookie设置标记
    public function verify(){
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        //正则匹配cookie并使用
        preg_match('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配  
        $cookie = trim($str[1]); //获得COOKIE（SESSIONID）
        //$arr=explode("=", $cookie);
        //print_r($arr);
        //setcookie($arr[0],$arr[1]);
        //curl_setopt($ch,CURLOPT_COOKIE,$cookie);
        curl_close($ch);
        //echo $content."<br>";
        /*
            登陆并设置新的TWFID和ENABLE_RANDCODE获取重定向地址
        */
        $arr=array(); 
        $arr[0][]="311408000107";
        $arr[0][]="155506";
        $arr[1][]="311502020328";
        $arr[1][]="202570";
        $arr[2][]="311505000609";
        $arr[2][]="196443";
        $arr[3][]="311405040126";
        $arr[3][]="261037";
        $arr[4][]="311413030118";
        $arr[4][]="093815";
        $arr[5][]="311509020427";
        $arr[5][]="190137";
        $arr[6][]="311410040223";
        $arr[6][]="100624";
        $ran=rand(0,6);
        $ch=curl_init();
        $post="mitm_result=&svpn_name=".$arr[$ran][0]."&svpn_password=".$arr[$ran][1]."&svpn_rand_code="; 
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/por/login_psw.csp?sfrnd=2346912324982305");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        //带上上登陆前的cookie
        curl_setopt($ch,CURLOPT_COOKIE,$cookie);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $content=curl_exec($ch);
        //正则匹配cookie并使用
        preg_match_all('/Set-Cookie:(.*);/iU',$content,$str1); //正则匹配  
        $cookie2=trim($str1[1][0]);
        $cookie3=trim($str1[1][1]);
        curl_setopt($ch, CURLOPT_COOKIE, "$cookie2;$cookie3");
        $arr3=explode("=", $cookie3);
        $arr2=explode("=", $cookie2);
        curl_close($ch);
        //登录教务处
        $ch=curl_init();
        $url="https://vpn.hpu.edu.cn/web/1/http/0/218.196.240.97/";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        //curl_setopt($ch,CURLOPT_COOKIEFILE, $cookieFile);
        //使用vpn登陆后的cookie
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie2;$cookie3"); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        //正则匹配教务处登陆时设置的cookie
        preg_match('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配  
        $cookie4 = trim($str[1]); //获得COOKIE（SESSIONID）
        $arr4=explode("=", $cookie4);
        //global $arr4; 
        //curl_setopt($ch, CURLOPT_COOKIE, $cookie4);
        //setcookie($arr4[0],$arr4[1]);
        curl_close($ch);

        //获取验证码
        $ch=curl_init();
        $url="https://vpn.hpu.edu.cn/web/0/http/1/218.196.240.97/validateCodeAction.do";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie2;$cookie3;$cookie4"); 
        setcookie("isl","1");
        setcookie($arr2[0],$arr2[1]);
        setcookie($arr3[0],$arr3[1]);
        setcookie($arr4[0],$arr4[1]);
        //print_r($_COOKIE);
        //curl_setopt($ch,CURLOPT_COOKIE,$cookie2); 
        //curl_setopt($ch,CURLOPT_COOKIEFILE,$cookieFile);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        curl_close($ch);
        echo $content;

        $xmlstr=$content;
        $openid=get_openid();
        if($openid=='-1'){
            $img_id='default'; 
        }else{
            $img_id=$openid;
        }
        $filename=$img_id.".jpg";//要生成的图片名字
        //$xmlstr = file_get_contents('https://niool.com/weixin/index.php?s=/addon/JiDian/JiDian/verify');
        //echo $xmlstr;
        //echo $this->StrToBin($xmlstr);
        $jpg = $xmlstr;//得到post过来的二进制原始数据
        $file = fopen("Verify/".$filename,"w");//打开文件准备写入
        fwrite($file,$jpg);//写入
        fclose($file);//关闭*/

    }

    //登录页面
    public function login(){   
        $user=M('user');
        $openid=get_openid();
        $mm = $user->where("openid=".'"'.$openid.'"')->getField('password');
        $studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        $this->assign('mm',$mm);
        $this->assign('studentid',$studentid);
        $this->display();
    }

    //本学期成绩
    public function index(){
        if(isset($_COOKIE['isl'])){
            $cookie4="websvr_cookie"."=".$_COOKIE['websvr_cookie'];
            $cookie2="ENABLE_RANDCODE"."=".$_COOKIE['ENABLE_RANDCODE'];
            $cookie3="TWFID"."=".$_COOKIE['TWFID'];
        }
        if(isset($_POST['submit'])){
            $openid=$_POST['openid'];
            $zjh=$_POST['zjh'];
            $mm=$_POST['mm'];
            //$v_yzm=$_POST['v_yzm'];
        }

        $ch=curl_init();
        $openid=get_openid();
        if($openid=='-1'){
            $img_id='default'; 
        }else{
            $img_id=$openid;
        }
        $post="url=https://blog.niool.com/weixin/Verify/".$img_id.".jpg&service=OcrKingForCaptcha&language=eng&charset=7&apiKey=7a035b90a8142c343eq9CThVaZK2nbB9kYb1LrOeCxqtH0wl7upz8Hk8pii90sXv6e1kd6qHQ9&type=http://niool.com/weixin/Verify/".$img_id.".jpg";  
        curl_setopt($ch,CURLOPT_URL,"http://lab.ocrking.com/ok.html");
        //curl_setopt($ch,CURLOPT_REFERER,"http://lab.ocrking.com/");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        //带上上登陆前的cookie
        //curl_setopt($ch,CURLOPT_COOKIE,"$cookie_1;$cookie_2");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $content1=curl_exec($ch);
        curl_close ($ch);
        preg_match("/<result>(.*?)<\/result>/si", $content1,$str1);
        $verify=$str1[1];

        $params = array (
            'zjh' => $zjh,
            'mm' => $mm,
            'v_yzm' => $verify 
            );  


        $ch = curl_init ();
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/loginAction.do");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/web/1/http/0/218.196.240.97/");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie2;$cookie3;$cookie4");
        //curl_setopt($ch,CURLOPT_COOKIE,$cookie1); 
        //curl_setopt($ch,CURLOPT_COOKIE,$cookie2);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $content=curl_exec($ch);
        curl_close ( $ch );


        $ch = curl_init ();
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/gradeLnAllAction.do?type=ln&oper=sxinfo&lnsxdm=001#qb_001");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/loginAction.do");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie2;$cookie3;$cookie4");
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $content=curl_exec($ch);
        curl_close ( $ch );
        //对页面进行排序
        $content=iconv("gbk", "utf-8", $content);
        $html=new SimpleHtmlController();
        $html->load($content);
        //必修
        $table=$html->find('table')[5];
        $arr1=$this->get_td_array($table);//执行函数
        //选修
        @$table=$html->find('table')[13];
        if($table){
            $arr2=$this->get_td_array($table);//执行函数
        }else{
            $arr2=array();
        }
        //任选
        @$table=$html->find('table')[22];
        if($table){
            $arr3=$this->get_td_array($table);//执行函数
        }else{
            $arr3=array();
        }
        $arr=array_merge($arr1,$arr2,$arr3);
        $data=array();
        foreach($arr as $v){
            if(count($v)<6){
                unset($v);
            }else{
                $data[]=$v;
            }
        }
        $this->assign('arr',$data);
        $this->display();
    }

    //正则匹配表格
    public function get_td_array($table) { 
        $table = preg_replace("'<table[^>]*?>'si","",$table); 
        $table = preg_replace("'<tr[^>]*?>'si","",$table); 
        $table = preg_replace("'<td[^>]*?>'si","",$table); 
        $table = str_replace("</tr>","{tr}",$table); 
        //PHP开源代码
        $table = str_replace("</td>","{td}",$table); 
        //去掉 HTML 标记  
        $table = preg_replace("'<[/!]*?[^<>]*?>'si","",$table); 
        //去掉空白字符   
        $table = preg_replace("'([rn])[s]+'","",$table); 
        $table = str_replace(" ","",$table); 
        $table = str_replace("&nbsp;","",$table); 
        $table = str_replace(" ","",$table);
        $table = explode('{tr}', $table); 
        array_pop($table);  
        foreach ($table as $key=>$tr) { 
            $td = explode('{td}', $tr); 
            array_pop($td); 
            $td_array[] = $td; 
        } 
        return $td_array; 
    }
   
}
