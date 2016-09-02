<?php

namespace Addons\Cet4\Controller;
use Home\Controller\AddonsController;

class Cet4Controller extends AddonsController{
    public function cet(){
        $rs=curl_init();
        //post提交
        $url="http://cet.redrock-team.com/api/v2/config";
        $post="name=白永涛&school=河南理工大学&cetType=1"; 
        curl_setopt($rs,CURLOPT_URL,$url);
        //post数据来源
        curl_setopt($rs,CURLOPT_REFERER,"http://cet.redrock-team.com/");
        curl_setopt($rs,CURLOPT_POST,1);
        curl_setopt($rs,CURLOPT_POSTFIELDS,$post);
        //设置cookie
        $cookie1="pgv_pvi=7795860480";
        $cookie2="pgv_si=s416871424";
        curl_setopt($rs,CURLOPT_COOKIE,"$cookie1;$cookie2");
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
        //跳转到数据页面
        curl_exec($rs);
        curl_setopt($rs,CURLOPT_URL,"http://cet.redrock-team.com/api/v2/noticket");
        curl_setopt($rs,CURLOPT_REFERER,"http://cet.redrock-team.com/");
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($rs);
        curl_close($rs);
        // /echo $content;
        $arr=json_decode($content,true);
        //$html = new simple_html_dom();
        print_r($arr);
        
    }
}
