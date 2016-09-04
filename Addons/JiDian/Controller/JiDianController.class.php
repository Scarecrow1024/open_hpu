<?php

namespace Addons\JiDian\Controller;
use Home\Controller\AddonsController;

class JiDianController extends AddonsController{
    //登录vpn获取验证码并且保存cookie设置标记
    public function verify(){
    	header("Content-Type:image/jpg");
        /*$ch=curl_init();
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
        /*$arr=array(); 
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
        $ran=rand(0,10);*/
        $arr=$this->gett();
        foreach($arr as $k=>$v){
            $rand0=$k;
            $rand1=substr($v,12);
        }

        $ch=curl_init();
        $post="mitm_result=&svpn_name=".$rand0."&svpn_password=".$rand1."&svpn_rand_code="; 
        
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/por/login_psw.csp?sfrnd=2346912324982305");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        //带上上登陆前的cookie
        //curl_setopt($ch,CURLOPT_COOKIE,$cookie);
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

    public function auto_verify(){
        $ch=curl_init();

        $post="url=http://niool.com/weixin/Verify/default.jpg&service=OcrKingForCaptcha&language=eng&charset=7&apiKey=7a035b90a8142c343eq9CThVaZK2nbB9kYb1LrOeCxqtH0wl7upz8Hk8pii90sXv6e1kd6qHQ9&type=http://niool.com/weixin/Verify/default.jpg";  
        curl_setopt($ch,CURLOPT_URL,"http://s.ocrking.net:6080/ok.html");
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
        echo $verify;
    }

    public function lala(){
        $rand=rand(1000,10000);
        $user=D('user');
        $where=array(
            'id'=>array('eq',$rand),
            'IdCard'=>array('gt',0),
            );
        $list = $user->where($where)->getField('studentid,IdCard');
        return $list;
    }

    public function gett(){
        $ret=$this->lala();
        if(count($ret)==1){
            foreach($ret as $k=>$v){
                if($k==0||$v<311300000000||$k==''){
                    $this->gett();
                }else{
                    return $ret;
                }
            }
        }else{
            $this->gett();
        }
    }

    public function ai(){
        print_r($this->gett());
    }

    public function jidian(){  
        /*$ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,"http://lab.ocrking.com/");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        curl_close ($ch);
        //正则匹配cookie并使用
        preg_match_all('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配  
        $cookie_1 = trim($str[1][0]);
        $cookie_2 = trim($str[1][1]);*/
        $ch=curl_init();
        $openid=get_openid();
        if($openid=='-1'){
            $img_id='default'; 
        }else{
            $img_id=$openid;
        }
        $post="url=http://niool.com/weixin/Verify/".$img_id.".jpg&service=OcrKingForCaptcha&language=eng&charset=7&apiKey=7a035b90a8142c343eq9CThVaZK2nbB9kYb1LrOeCxqtH0wl7upz8Hk8pii90sXv6e1kd6qHQ9&type=http://niool.com/weixin/Verify/".$img_id.".jpg";  
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
        //header("charset=utf-8");

        if(isset($_COOKIE['isl'])){
            $cookie4="websvr_cookie"."=".$_COOKIE['websvr_cookie'];
            $cookie2="ENABLE_RANDCODE"."=".$_COOKIE['ENABLE_RANDCODE'];
            $cookie3="TWFID"."=".$_COOKIE['TWFID'];
        }
        if(isset($_COOKIE['TWFID'])){
            $cookie3="TWFID"."=".$_COOKIE['TWFID'];
        }
        if(isset($_POST['submit'])){
            $openid=$_POST['openid'];
            $zjh=$_POST['zjh'];
            $mm=$_POST['mm'];
            //$v_yzm=$_POST['v_yzm'];
        }

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

        $url = "https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/gradeLnAllAction.do?type=ln&oper=sxinfo&lnsxdm=001#qb_001";
        $ch = curl_init ();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/loginAction.do");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie2;$cookie3;$cookie4");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec ( $ch );
        curl_close ( $ch );
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
        //print_r($arr);
        $str=json_encode($arr,JSON_UNESCAPED_UNICODE);
        //echo $str;
        $str=str_replace("中等", "75.0",$str);
        $str=str_replace("良好", "85.0",$str);
        $str=str_replace("优秀", "95.0",$str);
        $str=str_replace("及格", "60.0",$str);
        $arr=json_decode($str);
        //$arr=unserialize(preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'",  $str));
        //print_r($arr);
        $data1=array();
        $data2=array();
        $data3=array();
        foreach($arr as $k=>$v){
            if(count($v)>6){
                //上海交大4.3
                if($v[6]>=95&&$v[6]<=100){
                    $data1[0][]=$v[4]*4.3;
                }else if($v[6]>=90&&$v[6]<=94){
                    $data1[1][]=$v[4]*4.0;
                }else if($v[6]>=85&&$v[6]<=89){
                    $data1[2][]=$v[4]*3.7;
                }else if($v[6]>=80&&$v[6]<=84){
                    $data1[3][]=$v[4]*3.3;
                }else if($v[6]>=75&&$v[6]<=79){
                    $data1[4][]=$v[4]*3.0;
                }else if($v[6]>=70&&$v[6]<=74){
                    $data1[5][]=$v[4]*2.7;
                }else if($v[6]>=67&&$v[6]<=69){
                    $data1[6][]=$v[4]*2.3;
                }else if($v[6]>=65&&$v[6]<=66){
                    $data1[7][]=$v[4]*2.0; 
                }else if($v[6]>=62&&$v[6]<=64){
                    $data1[9][]=$v[4]*1.7;
                }else if($v[6]>=60&&$v[6]<=61){
                    $data1[10][]=$v[4]*1.0;
                }else if($v<60){
                    $data1[11][]=$v[4]*0;
                }
                //标准绩点计算
                if($v[6]>=90&&$v[6]<=100){
                    $data2[1][]=$v[4]*4.0;
                }else if($v[6]>=85&&$v[6]<=89){
                    $data2[2][]=$v[4]*3.7;
                }else if($v[6]>=82&&$v[6]<=84){
                    $data2[3][]=$v[4]*3.3;
                }else if($v[6]>=78&&$v[6]<=81){
                    $data2[4][]=$v[4]*3.0;
                }else if($v[6]>=75&&$v[6]<=77){
                    $data2[5][]=$v[4]*2.7;
                }else if($v[6]>=72&&$v[6]<=74){
                    $data2[6][]=$v[4]*2.3;
                }else if($v[6]>=68&&$v[6]<=71){
                    $data2[7][]=$v[4]*2.0; 
                }else if($v[6]>=64&&$v[6]<=67){
                    $data2[8][]=$v[4]*1.5;
                }else if($v[6]>=60&&$v[6]<=63){
                    $data2[9][]=$v[4]*1.0;
                }else if($v<60){
                    $data2[10][]=$v[4]*0;
                }
                //中科大4.3
                if($v[6]>=95&&$v[6]<=100){
                    $data3[0][]=$v[4]*4.3;
                }else if($v[6]>=90&&$v[6]<=94){
                    $data3[1][]=$v[4]*4.0;
                }else if($v[6]>=85&&$v[6]<=89){
                    $data3[2][]=$v[4]*3.7;
                }else if($v[6]>=82&&$v[6]<=84){
                    $data3[3][]=$v[4]*3.3;
                }else if($v[6]>=78&&$v[6]<=81){
                    $data3[4][]=$v[4]*3.0;
                }else if($v[6]>=75&&$v[6]<=77){
                    $data3[5][]=$v[4]*2.7;
                }else if($v[6]>=72&&$v[6]<=74){
                    $data3[6][]=$v[4]*2.3;
                }else if($v[6]>=68&&$v[6]<=71){
                    $data3[7][]=$v[4]*2.0; 
                }else if($v[6]>=65&&$v[6]<=67){
                    $data3[8][]=$v[4]*1.7;
                }else if($v[6]>=62&&$v[6]<=64){
                    $data3[9][]=$v[4]*1.5;
                }else if($v[6]>=61&&$v[6]<=63){
                    $data3[10][]=$v[4]*1.3;
                }else if($v[6]==60){
                    $data3[11][]=$v[4]*1.0;
                }else if($v<60){
                    $data3[12][]=$v[4]*0;
                }
                $data['fen'][]=$v[4];
            }
        }
        //print_r($arr[count($data['fen'])+1]);
        $h1=array();
        $h2=array();
        $h3=array();
        foreach($data1 as $v){
            $h1[]=array_sum($v)."<br>";
        }
        foreach($data2 as $v){
            $h2[]=array_sum($v)."<br>";
        }
        foreach($data3 as $v){
            $h3[]=array_sum($v)."<br>";
        }
        $fen=array_sum($data['fen']);
        
        $user=M('user');
        $name = $user->where("openid=".'"'.$openid.'"')->getField('name');
        $this->assign('name',$name);
        
        $fen=array_sum($data['fen']);
        $con=count($data['fen']);
        $this->assign('con',$con);
        $this->assign("fen",$fen);
        $this->assign("h1",$h1);
        $this->assign("h2",$h2);
        $this->assign("h3",$h3);
        setcookie("isl",null);
        setcookie($cookie2,null);
        setcookie($cookie3,null);
        setcookie($cookie4,null);
        $this->display();
    }
    
    
    function get_td_array($table) { 
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
