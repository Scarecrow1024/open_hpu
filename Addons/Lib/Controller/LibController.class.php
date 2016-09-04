<?php

namespace Addons\Lib\Controller;
use Home\Controller\AddonsController;

class LibController extends AddonsController{
    public function ser(){
        $this->display();
    }
    
    public function gccx(){
        $rs=curl_init();
        //post提交
        $url="http://218.196.244.90:8080/bmls.php";
        $t5=$_POST['bookname'];
        $t=iconv("utf-8","gbk",$t5);
        $post="T1=1&T2=2&T4=16&T3=9&T5=".$t; 
        curl_setopt($rs,CURLOPT_URL,$url);
        //post数据来源
        curl_setopt($rs,CURLOPT_REFERER,"http://218.196.244.90:8080/bml.php");
        curl_setopt($rs,CURLOPT_POST,1);
        curl_setopt($rs,CURLOPT_POSTFIELDS,$post);
        //跳转到数据页面
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($rs);
        curl_close($rs);
        //echo $content;
        //解析html dom
        $html = new SimpleHtmlController();
        //获取图书信息
        $html->load($content);
        $info=$html->find('table')[5]->find('table')[7];
        $text=$info->find('tr');
        $arr=array();
        foreach($text as $v){
            foreach($v->find('td') as $k){
                $arr[]=$k->plaintext;
            }
        }
        //print_r($arr);
        $con1=count($arr);
        //获取图书文本信息
        $bookInfo=array();
        $j=0;
        for($i=6;$i<$con1;$i++){
            $bookInfo[$j][$i%6]=$arr[$i];
        // echo "&nbsp";
            if((($i+1)%6)==0){
                $j=$j+1;
            // echo "<br>";
            }
        }
        //print_r($bookInfo);
        $arrinfo=array();
        foreach($bookInfo as $v){ 
            $arrinfo[]="书名:".$v[1]."<br>"."作者:".$v[2]."<br>"."索书号:".$v[3]."<br>";
        }
        //print_r($arrinfo);

        //获取图书链接
        $info=$html->find('table')[5]->find('table')[7];
        $text=$info->find('tr');
        $arr=array();
        foreach($text as $v){
            foreach($v->find('a') as $k){
                $arr[]="http://218.196.244.90:8080/".$k->href;
            }
        }
        $con2=count($arr);
        //print_r($arr);
        $bookUrl=array();
        $j=0;
        for($i=0;$i<$con2;$i++){
            $bookUrl[$j][$i%3]=$arr[$i];
        // echo "&nbsp";
            if((($i+1)%3)==0){
                $j=$j+1;
            // echo "<br>";
            }
        }
        //echo "<pre>";
        //print_r($bookUrl);
        $arr=array();
        foreach($bookUrl as $v){
            $arr[]=$v[0]."<br>";
        }
        //print_r($arr);
        
        $arrurl=array();
        foreach($bookUrl as $v){
            $arrurl[]=$this->curl($v[0]);
        }
        //print_r($arrurl);

        //echo curl("http://218.196.244.90:8080/ml1-1.php?bringout=0301387810+TP312PH%2BC472.2");
        $con3=count($arrurl);
        $content="";
        for($i=0;$i<$con3;$i++){
            $content.= $arrinfo[$i].$arrurl[$i]."<br>";
        }
        //echo $content;

        $arr=array();
        for($i=0;$i<16;$i++){
            $arr[]=$arrinfo[$i].$arrurl[$i];
        }
        //print_r($arr);
        $this->assign('con',$con3);
        $this->assign('arr',$arr);
        $this->display();

    }
    
    public function xujie(){
        $user=M('user');
        $openid=get_openid();
        $studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        $xjh=$_GET['xjh'];
        $ch=curl_init();
        $post="netxj%5B%5D=".$xjh."%2F%2F30%2F%2F0&T1=".$studentid."&Submit=%D0%F8%BD%E8%D1%A1%D6%D0%CD%BC%CA%E9";
        $url="http://218.196.244.90:8080/bkxj1.php";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        $content=iconv('GB2312', 'UTF-8', $content); 
        curl_close($ch);
        //$content=mb_convert_encoding($content, "UTF-8", "HTML-ENTITIES");
        //echo $content;
        //exit();
        if(strstr($content, "该书已预约")){
            $this->error('续借失败，该书已被预约', U('/addon/Lib/Lib/grzx/'));
        }else{
            $this->success('续借成功', U('/addon/Lib/Lib/grzx/'));
        }
        //echo $post;
        
    }
        
    
    public function curl($url){
        $rs=curl_init();
        //post提交
        curl_setopt($rs,CURLOPT_URL,$url);
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
        $content=curl_exec($rs);
        curl_close($rs);
        $content=iconv('gbk','utf-8',$content);
        //echo $content;
        //匹配地点
        preg_match('/南馆(.*)位于 南校区图书馆(.*)楼([\x{4e00}-\x{9fa5}]){1}/u',$content,$arr);
        $didian=$arr[0];
        preg_match_all('/南馆/u',$content,$data);
        //总共多少册
        $con=count($data[0])-3;
        //echo "本书共".$con-1."册 其中x册可借";
        preg_match_all('/已借出/u',$content,$array);
        //已借出
        $con1=count($array[0])-1;
        $info="";
        $info.=$didian."<br>";
        $info.="本书在南校区图书馆共".$con."册,已借出".$con1."册,余".($con-$con1)."册可借";
        /*$html = new SimpleHtmlController();
        $html->load($content);
        //$info1=$html->find('table')[5]->find('table')[9]->find('tr')[8];
        $info2=$html->find('table')[5]->find('table')[9]->lastchild ();
        //echo $info1->plaintext."<br>";
        $info="";
        $info.=$didian."<br>";
        echo $info;
        exit;
        $info.=$info2->plaintext."<br>"."<br>";
        return $info;*/
        return $info;
    }
    
    public function grzx(){
        $time=date('G')*3600+date('i')*60+date('s')-300;
        $tday1=$time;
        $user=M('user');
        $openid=get_openid();
        $studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        $ch=curl_init();
        $post="dzh=".$studentid."&tday1=".$tday1."&submit1=";
        $url="http://218.196.244.90:8080/dzff.php";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        curl_close($ch);
        
        $html=new SimpleHtmlController();
        $html->load($content);
        $table=$html->find('table')[5];
        $arr=$this->get_td_array($table);//执行函数 
        //print_r($arr);
        $con=0;
        foreach($arr as $v){
              if(count($v)>=9){
                   $con+=1;
              }
        }
        if($con==0){
            $this->assign("con",$con);
        }else{
            $this->assign("con",$con-1);
        }
        $chaoqi=0;
        for($i=0;$i<$con;$i++){
            if($arr[28+$i][7]>0){
                $chaoqi+=1;
            }
        }
        $this->assign('arr',$arr);
        $this->assign('chaoqi',$chaoqi);
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
