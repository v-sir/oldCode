<?
include "./lib/smtp.class.php";
//include "./lib/DB.class.php";
$smtpServer=@$_GET['smtpServer'];
$mailUserName=@$_GET['mailUserName'];
$mailPassWord=@$_GET['mailPassWord'];
$port=@$_GET['port'];
$isSecurity=@$_GET['isSecurity'];
$title=@$_GET['title'];
$content=@$_GET['content'];
$from=@$_GET['from'];
$to=@$_GET['to'];
$Cc=@$_GET['Cc'];
$Bcc=@$_GET['Bcc'];
$NO=@$_GET['NO'];
$operator=@$_GET['operator'];
$StartTime=@$_GET['StartTime']/1000-10;
$StopTime=@$_GET['StopTime']/1000+10;
$DB=new DB("mail");
$sql = "select  mediaID from addAttachment  where operator='$operator' && status=0 && errMsg=0 && $StartTime<=create_at && create_at<=$StopTime && NO='$NO'";
$result=$DB->Query($sql);

while($row=$result->fetch_assoc()){
    @$mediaID[]=$row;

}//成功数据



if(isset($smtpServer) && $smtpServer!="" && isset($mailUserName) && $mailUserName!="" && isset($mailPassWord) && $mailPassWord!="" && isset($title) && $title!="" && isset($content) && $content!="" && isset($from) && $from!="" && isset($to) && $to!=""){

   Send($smtpServer,$mailUserName,$mailPassWord,$from,$to,$Cc,$Bcc,$mediaID,$title,$content,$operator);

}
else{
    exit('{"code":65535,"msg":"Missing Parameters"}');
}

function Send($smtpServer,$mailUserName,$mailPassWord,$from,$to,$Cc,$Bcc,$mediaID,$title,$content,$operator){
    $sendmail=new MySendMail();
    $sendmail->setServer($smtpServer,$mailUserName,$mailPassWord,465,1);
    $sendmail->setFrom($from);
    $sendmail->setReceiver($to);
    $sendmail->setCc($Cc);
    $sendmail->setCc($Bcc);
    $sendmail->operator=$operator;
//print_r($mediaID);
    for($i=0;$i<count($mediaID);$i++){
        $filename=$mediaID[$i]['mediaID'].".jpg";
        $sendmail->addAttachment("./attachment/".$filename);
    }

    // $sendmail->addAttachment("fx.png");
    $sendmail->setMail($title,$content);
    $sendmail->sendMail();
}

?>