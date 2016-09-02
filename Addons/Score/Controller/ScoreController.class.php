<?php

namespace Addons\Score\Controller;
use Home\Controller\AddonsController;

class ScoreController extends AddonsController{
    //登录vpn获取验证码并且保存cookie设置标记
    public function verify(){
        set_time_limit(0);
    
        //获取TWFID的cookie值
        $snoopy = new SnoopyController();
        $snoopy->cookies["ENABLE_RANDCODE"] = ' 0';
        $snoopy->fetch('https://vpn.hpu.edu.cn/por/login_psw.csp'); //获取所有内容

        $cookies1=$snoopy->headers;
        preg_match('/Set-Cookie:(.*);/iU',$cookies1[4],$cookies1); //正则匹配  
        //print_r($cookies1);
        $arr1=explode('=', $cookies1[1]);
        $cookie1=$arr1[1];


        //获取websvr_cookie的cookie值
        $snoopy = new SnoopyController();
        $snoopy->referer='https://vpn.hpu.edu.cn/por/login_psw.csp';//例如：http://www.baidu.com/

        $snoopy->agent="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0)";

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
        $arr[7][]="311503000512";
        $arr[7][]="083715";
        $arr[8][]="311402010418";
        $arr[8][]="030019";
        $arr[9][]="311508071030";
        $arr[9][]="300012";
        $arr[10][]="311503020105";
        $arr[10][]="217724";
        $ran=rand(0,10);

        $post['mitm_result']="";
        $post['svpn_name']=$arr[$ran][0];
        $post['svpn_password']=$arr[$ran][1];
        $post['svpn_rand_code']="";

        $url='https://vpn.hpu.edu.cn/por/login_psw.csp?sfrnd=2346912324982305';//登陆数据提交的URL地址
        $snoopy->cookies["ENABLE_RANDCODE"] = ' 0';
        $snoopy->cookies["TWFID"] = $cookie1;
        $snoopy->submit($url,$post);

        $snoopy->fetch("https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97");//希望获取的页面数据
        $snoopy->cookies["ENABLE_RANDCODE"] = ' 0';
        $snoopy->cookies["TWFID"] = $cookie1;
        $cookies2=$snoopy->headers;
        preg_match('/Set-Cookie:(.*);/iU',$cookies2[5],$cookies2); //正则匹配  
        $arr2=explode('=', $cookies2[1]);
        $cookie2=$arr2[1];

        //获取验证码
        $snoopy->referer='https://vpn.hpu.edu.cn/por/login_psw.csp';

        $snoopy->agent="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0)";

        $snoopy->cookies["ENABLE_RANDCODE"] = ' 0';
        $snoopy->cookies["TWFID"] = $cookie1;
        $snoopy->cookies["websvr_cookie"] = $cookie2;
        setcookie('isl','1');
        setcookie("TWFID",$cookie1);
        setcookie("ENABLE_RANDCODE",' 0');
        setcookie("websvr_cookie",$cookie2);
        //print_r($_COOKIE);

        $snoopy->fetch("https://vpn.hpu.edu.cn/web/0/http/1/218.196.240.97/validateCodeAction.do");

        echo $snoopy->results;

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

    // 取得全部的成绩
    function index($cookie1,$cookie2,$cookie3){
        $snoopy=new SnoopyController();

        $snoopy->referer='https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97';

        $snoopy->agent="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0)";

        $post['zjh']=$_POST['zjh'];
        $post['mm']=$_POST['mm'];
        $post['v_yzm']=$_POST['v_yzm'];

        $snoopy->cookies["websvr_cookie"] = $_COOKIE['websvr_cookie'];
        $snoopy->cookies["ENABLE_RANDCODE"] = $_COOKIE['ENABLE_RANDCODE'];
        $snoopy->cookies["TWFID"] = $_COOKIE['TWFID'];

        $url='https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/loginAction.do';//登陆数据提交的URL地址

        $snoopy->submit($url,$post);

        $snoopy->fetch("https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/gradeLnAllAction.do?type=ln&oper=sxinfo&lnsxdm=001#qb_001");//希望获取的页面数据
        //print_r($_COOKIE);
        $content = $snoopy->results;//输出结果，登陆成功
        setcookie('websvr_cookie', NULL);
        setcookie('ENABLE_RANDCODE', NULL);
        setcookie('TWFID', NULL);
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
