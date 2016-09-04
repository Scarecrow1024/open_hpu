<?php

namespace Addons\Notice\Controller;
use Home\Controller\AddonsController;

class NoticeController extends AddonsController{
    public function demo01(){
        //获取讲座信息
        $rs=curl_init();
        curl_setopt($rs,CURLOPT_URL,'http://218.196.240.155/myweb/');
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($rs);
        curl_close($rs);
        $content=iconv('utf-8', 'gbk', $content);
        //echo $content;
        $html=new SimpleHtmlController();
        $html->load($content);
        $table=$html->find('table')[2];
        $arr=$this->get_td_array($table);
        print_r($arr);
    }

    public function notice(){
        //获取最新公告
        $rs=curl_init();
        //post提交
        $url="http://my.hpu.edu.cn/userPasswordValidate.portal";
        $post="Login.Token1=311309010130&Login.Token2=024361&goto=http%3A%2F%2Fmy.hpu.edu.cn%2FloginSuccess.portal&gotoOnFail=http%3A%2F%2Fmy.hpu.edu.cn%2FloginFailure.portal"; 
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
        curl_setopt($rs,CURLOPT_URL,"http://my.hpu.edu.cn/LatestNewsList_3.jsp");
        curl_setopt($rs,CURLOPT_REFERER,"http://my.hpu.edu.cn/index.portal");
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($rs);
        preg_match_all('/<a href=\"(.*?)\".*?>(.*?)<\/a>/i', $content, $arr1);
        preg_match_all('/<span>(.*?)<\/span>/i', $content, $arr2);
        //print_r($arr2[1]);

        for($i=0;$i<=31;$i++){
            if(($i%2)==0){
                unset($arr1[2][$i]);
            }
        }
        $this->assign('arr21',$arr2[1]);
        $this->assign('arr12',$arr1[2]);
        //print_r($arr1[2]);
        //获取讲座信息
        $rs=curl_init();
        curl_setopt($rs,CURLOPT_URL,'http://218.196.240.155/myweb/');
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($rs);
        curl_close($rs);
        $html=new SimpleHtmlController();
        $html->load($content);
        $table=$html->find('table')[2];
        $arr1=$this->get_td_array($table);
        unset($arr1[0]);
        unset($arr1[16]);
        $this->assign('arr1',$arr1);
        $this->display();
    }

    public function demo02(){
        //获取最新公告
        $rs=curl_init();
        //post提交
        $url="http://my.hpu.edu.cn/userPasswordValidate.portal";
        $post="Login.Token1=311309010130&Login.Token2=024361&goto=http%3A%2F%2Fmy.hpu.edu.cn%2FloginSuccess.portal&gotoOnFail=http%3A%2F%2Fmy.hpu.edu.cn%2FloginFailure.portal"; 
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
        curl_setopt($rs,CURLOPT_URL,"http://my.hpu.edu.cn/LatestNewsList_3.jsp");
        curl_setopt($rs,CURLOPT_REFERER,"http://my.hpu.edu.cn/index.portal");
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($rs);
        $content=iconv('utf-8', 'gbk', $content);
        preg_match_all('/<a href=\"(.*?)\".*?>(.*?)<\/a>/i', $content, $arr1);
        preg_match_all('/<span>(.*?)<\/span>/i', $content, $arr2);
        print_r($arr2[1]);

        for($i=0;$i<=31;$i++){
            if(($i%2)==0){
                unset($arr1[2][$i]);
            }
        }
        
        print_r($arr1[2]);

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
