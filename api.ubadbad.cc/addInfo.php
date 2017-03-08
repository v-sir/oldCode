<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2016/10/13
 * Time: 22:27
 */
include "./lib/DB.class.php";
@$name=$_GET['name'];
@$email=$_GET['email'];
@$NO=$_GET['NO'];
$DB=new DB("mail");
$sql="insert into info(NO,email,name) values('$NO','$email','$name')";
if(isset($name)&& isset($email) && isset($NO)){
    $result=$DB->Query($sql);
    if($result){
        $data=array(
            'code'=>0,
            'msg'=>"OK!"
        );
        echo json_encode($data);
    }
}
else{
    exit('{"code":65535,"msg":"Missing Parameters"}');
}




?>