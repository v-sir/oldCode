<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2016/8/15
 * Time: 18:38
 */
include "./lib/SmartQQ.class.php";
$qr_img=new SmartQQ();

$qr_img->CheckLogin("https://ssl.ptlogin2.qq.com/ptqrlogin?webqq_type=10&remember_uin=1&login2qq=1&aid=501004106&u1=http%3A%2F%2Fw.qq.com%2Fproxy.html%3Flogin2qq%3D1%26webqq_type%3D10&ptredirect=0&ptlang=2052&daid=164&from_ui=1&pttype=1&dumy=&fp=loginerroralert&action=0-0-26340&mibao_css=m_webqq&t=undefined&g=1&js_type=0&js_ver=10167&login_sig=&pt_randsalt=0");
?>