<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2016/10/13
 * Time: 23:27
 */
include "./lib/DB.class.php";
@$operator=$_GET['operator'];
@$StartTime=$_GET['StartTime']/1000-10;
@$StopTime=$_GET['StopTime']/1000+10;
!empty($StartTime) && !empty($StopTime) && !empty($operator) ? search($operator,$StartTime,$StopTime) :error();
function search($operator,$StartTime,$StopTime)
{
    $DB = new DB("mail");
    $sql = "select* from addAttachment left join info on addAttachment.NO=info.NO where operator='$operator' && status=0 && errMsg=0 && $StartTime<=create_at && create_at<=$StopTime";
    $result = $DB->Query($sql);
    while ($row = $result->fetch_assoc()) {
        @$data[] = $row;

    }//Î´·¢ËÍÓÊÏäÊý¾Ý


    error(0, @$data,  $operator, $StartTime, $StopTime);


}
function error($error,$data,$operator,$StartTime,$StopTime){
    if($error==0){
        $total=count($data);
        //print_r($data);



        $data=array(
            'code'=>0,
            'msg'=>"OK!",
            'operator'=>$operator,
            'total'=>$total,
            'StartTime'=>date("Y-m-d H:i:s", $StartTime),
            'StopTime'=>date("Y-m-d H:i:s", $StopTime),

        );
        echo json_encode($data);
    }

}







    ?>