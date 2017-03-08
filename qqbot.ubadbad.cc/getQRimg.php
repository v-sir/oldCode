<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2016/8/15
 * Time: 18:24
 */
include "./lib/SmartQQ.class.php";
$qr_img=new SmartQQ();
$qr_img->getQRimg("https://ssl.ptlogin2.qq.com/ptqrshow?appid=501004106&e=0&l=M&s=5&d=72&v=4&t=0.9463507030369263");
?>