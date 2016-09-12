<?php

namespace Addons\SchoolCardLogin\Controller;
use Home\Controller\AddonsController;

class SchoolCardLoginController extends AddonsController{
    public function login(){
        $this->display();
    }

    public function ajaxcard(){
            $rs=curl_init();
            $studentid=$_POST['zjh'];
            $idcard=substr($_POST['mm'], 11, 6);
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
            echo $str;
    }
}
