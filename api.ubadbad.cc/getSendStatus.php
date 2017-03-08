<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2016/10/14
 * Time: 19:02
 */
include "./lib/DB.class.php";
@$operator=$_GET['operator'];
@$StartTime=$_GET['StartTime']/1000-10;
@$StopTime=$_GET['StopTime']/1000+10;
!empty($StartTime) && !empty($StopTime) && !empty($operator) ? search($operator,$StartTime,$StopTime) :error();
function search($operator,$StartTime,$StopTime){
    $DB=new DB("mail");
    $sql="select* from sendMail where operator='$operator'  && $StartTime<=create_at && create_at<=$StopTime && errMsg=211";
    $result=$DB->Query($sql);
    while($row=$result->fetch_assoc()){
        @$data[]=$row;

    }
   if(count($data)>=1){
       error(0,@$data,$operator,$StartTime,$StopTime);
   }
   else{
       error(-1,@$data,$operator,$StartTime,$StopTime);
   }







}

function error($error,$data,$operator,$StartTime,$StopTime){
    if($error==0){



        $data=array(
            'code'=>0,
            'msg'=>'ok!',
            'operator'=>$operator,
            'StartTime'=>date("Y-m-d H:i:s", $StartTime),
            'StopTime'=>date("Y-m-d H:i:s", $StopTime),

        );
        $DB=new DB("mail");
        $sql="insert into sendLog(operator,StartTime,StopTime) values('$operator','$StartTime','$StopTime')";
        $result=$DB->Query($sql);
        $sql="select * from sendLog  where operator='$operator'  && StartTime='$StartTime' && StopTime='$StopTime' ";
        $result=$DB->Query($sql);
        $row=$result->fetch_assoc();
        $total=$row['total']+1;
        $success=$row['success']+1;
        $fail=$row['fail']+1;
        $DB=new DB("mail");
        $sql="update sendLog set total='$total' where operator='$operator'  && StartTime='$StartTime' && StopTime='$StopTime' ";
        $result=$DB->Query($sql);
        $DB=new DB("mail");
        $sql="update sendLog set success='$success' where operator='$operator'  && StartTime='$StartTime' && StopTime='$StopTime' ";
        $result=$DB->Query($sql);
        $sql="update sendLog set fail='$fail' where operator='$operator'  && StartTime='$StartTime' && StopTime='$StopTime' ";
        //$result=$DB->Query($sql);

    }
    else{
        $data=array(
            'code'=>-1,
            'msg'=>'fail!',
            'operator'=>$operator,
            'StartTime'=>date("Y-m-d H:i:s", $StartTime),
            'StopTime'=>date("Y-m-d H:i:s", $StopTime),

        );
        $DB=new DB("mail");
        $sql="insert into sendLog(operator,StartTime,StopTime) values('$operator','$StartTime','$StopTime')";
        $result=$DB->Query($sql);
        $sql="select * from sendLog  where operator='$operator'  && StartTime='$StartTime' && StopTime='$StopTime' ";
        $result=$DB->Query($sql);
        $row=$result->fetch_assoc();
        $total=$row['total']+1;
        $success=$row['success']+1;
        $fail=$row['fail']+1;
        $DB=new DB("mail");
        $sql="update sendLog set total='$total' where operator='$operator'  && StartTime='$StartTime' && StopTime='$StopTime' ";
        $result=$DB->Query($sql);
        $DB=new DB("mail");
        $sql="update sendLog set success='$success' where operator='$operator'  && StartTime='$StartTime' && StopTime='$StopTime' ";
       // $result=$DB->Query($sql);
        $sql="update sendLog set fail='$fail' where operator='$operator'  && StartTime='$StartTime' && StopTime='$StopTime' ";
        $result=$DB->Query($sql);
    }
    echo json_encode($data);
}











?>