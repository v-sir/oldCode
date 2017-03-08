<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2016/10/12
 * Time: 21:30
 */
include "./lib/DB.class.php";
@$operator=$_GET['operator'];
@$StartTime=$_GET['StartTime']/1000-10;
@$StopTime=$_GET['StopTime']/1000+10;
!empty($StartTime) && !empty($StopTime) && !empty($operator) ? search($operator,$StartTime,$StopTime) :error();
function search($operator,$StartTime,$StopTime){
    $DB=new DB("mail");
    $sql="select* from addAttachment where operator='$operator' && errMsg=0 && $StartTime<=create_at && create_at<=$StopTime";
    $result=$DB->Query($sql);
    while($row=$result->fetch_assoc()){
        @$data[]=$row;

    }//成功数据
    $sql="select* from addAttachment where operator='$operator' && errMsg>0  && $StartTime<=create_at && create_at<=$StopTime";
    $result=$DB->Query($sql);
    while($row=$result->fetch_assoc()){
        @$data1[]=$row;

    }//失败数据
    $sql="select* from addAttachment where operator='$operator'  && $StartTime<=create_at && create_at<=$StopTime";
    $result=$DB->Query($sql);
    while($row=$result->fetch_assoc()){
        @$data2[]=$row;

    }//全部数据

    error(0,@$data,@$data1,@$data2,$operator,$StartTime,$StopTime);




}
function logging($operator,$StartTime,$StopTime,$total,$success,$fail){
    $DB=new DB("mail");
    $sql="insert into logging(operator,StartTime,StopTime,total,success,fail) values('$operator','$StartTime','$StopTime','$total','$success','$fail')";
    $result=$DB->Query($sql);

}
function error($error,$data,$data1,$data2,$operator,$StartTime,$StopTime){
    if($error==0){
        $total=count($data2);
        $success=count($data);
        $fail=count($data1);
        logging($operator,$StartTime,$StopTime,$total,$success,$fail);
        //print_r($data2);
        $data=array(
            'code'=>0,
            'operator'=>$operator,
            'total'=>$total,
            'success'=>$success,
            'fail'=>$fail,
            'StartTime'=>date("Y-m-d H:i:s", $StartTime),
            'StopTime'=>date("Y-m-d H:i:s", $StopTime),
            'data'=>$data2
        );
        echo json_encode($data);
    }

}
?>