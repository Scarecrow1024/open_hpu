<?php

namespace Addons\Kclass\Controller;
use Home\Controller\AddonsController;

class KclassController extends AddonsController{
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
        $rs=curl_init();
        /*$user=M('user');
        $openid=get_openid();
        //$studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        $data = $user->where("openid=".'"'.$openid.'"')->find();
        //$idcard = $user->where("openid=".'"'.$openid.'"')->getField('IdCard');
        $idcard=$data['IdCard'];
        $studentid=$data['studentid'];
        $idcard=substr($idcard, 11, 6);*/
        //post提交
        $url="http://my.hpu.edu.cn/userPasswordValidate.portal";
        $post="Login.Token1=311309010130&Login.Token2=024361&goto=http%3A%2F%2Fmy.hpu.edu.cn%2FloginSuccess.portal&gotoOnFail=http%3A%2F%2Fmy.hpu.edu.cn%2FloginFailure.portal"; 
        curl_setopt($rs,CURLOPT_URL,$url);
        //post数据来源
        curl_setopt($rs,CURLOPT_REFERER,"http://my.hpu.edu.cn/login.portal");
        curl_setopt($rs,CURLOPT_POST,1);
        curl_setopt($rs,CURLOPT_POSTFIELDS,$post);  
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
        //跳转到数据页面
        curl_exec($rs);
        curl_setopt($rs,CURLOPT_URL,"http://my.hpu.edu.cn/viewschoolcalendar3.jsp");
        curl_setopt($rs,CURLOPT_REFERER,"http://my.hpu.edu.cn/index.portal");
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($rs);
        curl_close($rs);
        $content=strip_tags($content)."<br>";
        preg_match_all("/[0-9]+/", $content, $matches);
        //print_r($matches);
        $zhou=$matches[0][7];
        $day=date('w');
        $this->assign('day',$day);
        $this->assign('zhou',$zhou);
        $this->display();
    }   

    // 取得全部的成绩
    function get(){
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

        $snoopy->referer='https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/loginAction.do';
        $snoopy->agent="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0)";
        //post学期和校区
        $post1['zxxnxq']='2015-2016-1-1';
        $post1['zxXaq']='01';
        $snoopy->cookies["websvr_cookie"] = $_COOKIE['websvr_cookie'];
        $snoopy->cookies["ENABLE_RANDCODE"] = $_COOKIE['ENABLE_RANDCODE'];
        $snoopy->cookies["TWFID"] = $_COOKIE['TWFID'];
        $url='https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xszxcxAction.do?oper=xszxcx_lb';//登陆数据提交的URL地址
        $snoopy->submit($url,$post1);
        //echo $snoopy->results;


        $snoopy->referer='https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xszxcxAction.do?oper=xszxcx_lb';
        $snoopy->agent="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0)";
        //post教学楼
        $post2['zxJxl']='1';
        $snoopy->cookies["websvr_cookie"] = $_COOKIE['websvr_cookie'];
        $snoopy->cookies["ENABLE_RANDCODE"] = $_COOKIE['ENABLE_RANDCODE'];
        $snoopy->cookies["TWFID"] = $_COOKIE['TWFID'];
        $url='https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xszxcxAction.do?oper=ld';//登陆数据提交的URL地址
        $snoopy->submit($url,$post2);
        //echo $snoopy->results;

        $snoopy->referer='https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xszxcxAction.do?oper=xszxcx_lb';
        $snoopy->agent="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0)";
        //post周次
        $post3['zc6']='7';
        $snoopy->cookies["websvr_cookie"] = $_COOKIE['websvr_cookie'];
        $snoopy->cookies["ENABLE_RANDCODE"] = $_COOKIE['ENABLE_RANDCODE'];
        $snoopy->cookies["TWFID"] = $_COOKIE['TWFID'];
        $url='https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/xszxcxAction.do?oper=xzzc';//登陆数据提交的URL地址
        $snoopy->submit($url,$post3);
        //echo $snoopy->results;

        $snoopy->referer='https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xszxcxAction.do?oper=xszxcx_lb';
        $snoopy->agent="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0)";
        //post节次
        $post4['xq0']='1';
        $snoopy->cookies["websvr_cookie"] = $_COOKIE['websvr_cookie'];
        $snoopy->cookies["ENABLE_RANDCODE"] = $_COOKIE['ENABLE_RANDCODE'];
        $snoopy->cookies["TWFID"] = $_COOKIE['TWFID'];
        $url='https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/xszxcxAction.do?oper=xzxq';//登陆数据提交的URL地址
        $snoopy->submit($url,$post4);
        //echo $snoopy->results;

        $snoopy->referer='https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xszxcxAction.do?oper=ld';
        $snoopy->agent="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0)";
        //post以上可能post不准
        $post5['zxxnxq']='2015-2016-1-1';
        $post5['zxXaq']='01';
        $post5['zxJxl']='1';
        $post5['zxZc']='6';
        $post5['zxxq']='5';
        $post5['pageSize']='20';
        $post5['page']='1';
        $post5['currentPage']='1';
        $snoopy->cookies["websvr_cookie"] = $_COOKIE['websvr_cookie'];
        $snoopy->cookies["ENABLE_RANDCODE"] = $_COOKIE['ENABLE_RANDCODE'];
        $snoopy->cookies["TWFID"] = $_COOKIE['TWFID'];
        $url='https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xszxcxAction.do?oper=ld&xqh=01&jxlh=1';//登陆数据提交的URL地址
        $snoopy->submit($url,$post5);
        //echo $snoopy->results;

        $snoopy->referer='https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xszxcxAction.do?oper=ld';
        $snoopy->agent="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0)";
        //post节次
        $post6['jc+2']='3';
        $snoopy->cookies["websvr_cookie"] = $_COOKIE['websvr_cookie'];
        $snoopy->cookies["ENABLE_RANDCODE"] = $_COOKIE['ENABLE_RANDCODE'];
        $snoopy->cookies["TWFID"] = $_COOKIE['TWFID'];
        $url='https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/xszxcxAction.do?oper=xzjc';//登陆数据提交的URL地址
        $snoopy->submit($url,$post6);
        //echo $snoopy->results;

        $snoopy->referer='https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xszxcxAction.do?oper=ld&xqh=01&jxlh=1';
        $snoopy->agent="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0)";

        //print_r($_POST);
        //post节次
        $post7['zxxnxq']='2015-2016-1-1';
        $post7['zxXaq']='01';
        $post7['zxJxl']=$_POST['jxl'];
        $post7['zxZc']=$_POST['zhou'];
        $post7['zxxq']=$_POST['xingqi'];
        $post7['zxJc']=$_POST['jie'];
        $post7['pageSize']='50';
        $post7['page']='1';
        $post7['currentPage']='1';
        $snoopy->cookies["websvr_cookie"] = $_COOKIE['websvr_cookie'];
        $snoopy->cookies["ENABLE_RANDCODE"] = $_COOKIE['ENABLE_RANDCODE'];
        $snoopy->cookies["TWFID"] = $_COOKIE['TWFID'];
        $url='https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xszxcxAction.do?oper=tjcx';//登陆数据提交的URL地址
        $snoopy->submit($url,$post7);
        $content = $snoopy->results;
        $content=iconv('gbk', 'utf-8', $content);
        $html=new SimpleHtmlController();
        $html->load($content);
        $table=$html->find('table')[10];
        $arr=$this->get_td_array($table);//执行函数
        //print_r($arr);
        $con=count($arr);
        $this->assign('con',$con);
        $this->assign('arr',$arr);
        setcookie('websvr_cookie', NULL);
        setcookie('ENABLE_RANDCODE', NULL);
        setcookie('TWFID', NULL);
        $this->display('class');
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
