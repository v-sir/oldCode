<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2016/10/14
 * Time: 19:29
 */

include "./lib/DB.class.php";
@$operator=$_GET['operator'];
@$StartTime=$_GET['StartTime']/1000-10;
@$StopTime=$_GET['StopTime']/1000+10;
@$action=$_GET['action'];
@$total=$_GET['total'];
if(!empty($StartTime) && !empty($StopTime) && !empty($operator)){

    if($action=="addData"){
        logging($operator,$StartTime,$StopTime,$total,$isSuccess);

    }
    else{
        search($operator,$StartTime,$StopTime) ;
    }


}
function search($operator,$StartTime,$StopTime){
    $DB=new DB("mail");
    $sql="select* from sendMail where operator='$operator'  && $StartTime<=create_at && create_at<=$StopTime ";
    $result=$DB->Query($sql);
    while($row=$result->fetch_assoc()){
        @$data[]=$row;

    }
    $sql="select* from sendMail where operator='$operator' && errMsg=211  && $StartTime<=create_at && create_at<=$StopTime";
    $result=$DB->Query($sql);
    while($row=$result->fetch_assoc()){
        @$data1[]=$row;

    }


    error(0,@$data,@$data1,$operator,$StartTime,$StopTime);





}

function error($error,$data,$data1,$operator,$StartTime,$StopTime){
    if($error==0){
        $total=count($data);
        $success=count($data1);
        $fail=$total-$success;
        //logging($operator,$StartTime,$StopTime,$total,$success,$fail);
        //print_r($data2);
        $data=array(
            'code'=>0,
            'operator'=>$operator,
            'total'=>$total,
            'success'=>$success,
            'fail'=>$fail,
            'StartTime'=>date("Y-m-d H:i:s", $StartTime),
            'StopTime'=>date("Y-m-d H:i:s", $StopTime),

        );
        echo json_encode($data);
    }

}
?>