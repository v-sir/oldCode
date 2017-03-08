<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2016/10/14
 * Time: 21:55
 */
include "./lib/DB.class.php";
@$id=$_GET['id'];
$DB=new DB("mail");
$sql="select* from version where id='$id'";
$result=$DB->Query($sql);
$row=$result->fetch_assoc();
echo $row['version'];
?>