<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2016/9/6
 * Time: 14:39
 */
include "./config/dbconfig.php";
class DB{
    public $conn;
    public $result;
    public function __construct($database){
        $this->conn=new mysqli(HOST,USERNAME,PASSWORD,$database);
        $this->error();

    }
    public function Query($sql){
        $this->result=$this->conn->query($sql);
        return $this->result;

    }
    public function GetDataOne(){
        return $this->result->fetch_assoc();

    }
    public function GetDataAll(){
        while($row=$this->result->fetch_assoc()){
            $temp[]=$row;
        }
        return $temp;

    }
    public function error(){
        if($this->conn->error) {
            $data=array(
              'code'=>-2,
                'msg'=>$this->conn->error
            );
            exit(json_encode($data));

        }


    }

    public function __destruct(){
        $this->conn->close();

    }
}




?>