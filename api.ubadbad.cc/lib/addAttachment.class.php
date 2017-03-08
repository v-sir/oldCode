<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2016/9/25
 * Time: 22:48
 *
 * 附件上传类
 * 返回值说明：
 * code 40001 未选择文件
 * code 40002 上传错误,返回系统错误
 * code 40003 文件类型不被允许
 * code 40004 文件太大
 *code 0 上传成功返回 mediaID
 */
include "./lib/DB.class.php";
class addAttachment{
    var $isAllow;
    var $dir;
    var $filename;
   // var $suffix;

    public function __construct(){

        @$this->operator=$_GET['operator'];
        @$this->name= $_FILES["file"]["name"];
        @$this->type= $_FILES["file"]["type"];
        @$this->size= $_FILES["file"]["size"];
        @$this->tmp_name=$_FILES["file"]["tmp_name"];
        @$this->error= $_FILES["file"]["error"];
        @$this->fileData=$_FILES["file"];
        @$this->NO=explode(".",$this->name);
       // echo $this->NO[0];
        $this->allowFileType=array(
            'image/gif','image/jpeg','image/png','image/bmp','application/octet-stream','application/zip'

        );//从这里设置文件允许被上传的格式
        //session_start();
        @$this->_sProgressKey = strtolower(ini_get('session.upload_progress.prefix')).md5(time().$this->name);
        @$this->progress=$_SESSION['upload_progress_laruence']; //推送文件发送进度
        $this->allowFileSize=200000000;//从这里设置文件大小上限
        @$isGetProgressStatus=$_GET['isGetProgressStatus'];
        empty($isGetProgressStatus) ? $this->isChooseFile() : $this-> progress();



       // print_r($this->suffix);



    }
    public function isChooseFile(){



        isset($_FILES['file'] ) && $this->name!=""? $this->upload() : $this->error(40001);

    }
    public function upload(){
        $suffix=array(
            'image/gif'=>'.gif',
            'image/jpeg'=>'.jpg',
            'image/png'=>'.png',
            'image/bmp'=>'.bmp',
            'application/octet-stream'=>'.rar',
            'application/zip'=>'.zip'

        );
        $this->filename=md5(time().$this->name).$suffix["$this->type"];
        //print_r($this->suffix);
        //print_r($this->type);
        $this->dir=dirname(dirname(__FILE__))."/attachment/";
        if(!is_dir($this->dir)){
            mkdir($this->dir,0777);
        }
        $count=count($this->allowFileType);
        for($i=0;$i<$count;$i++){
            $this->allowFileType[$i]==$this->type ? $this->isAllow .=1 : $this->isAllow.=0;

        }
        //echo $this->isAllow."\n";
        strstr($this->isAllow,'1') ? $this->isAllow=1 : $this->isAllow=0;
       // echo $this->isAllow;
        $this->isAllow==1 ? $this->saveFile() : $this->error(40003);



    }
    public function saveFile(){
        if($this->size<$this->allowFileSize){
            $saveFile=move_uploaded_file($this->tmp_name,$this->dir.$this->filename);
            $saveFile==true ?  $this->error(0): $this->error(40002);


        }else{
            $this->error(40004);
        }

      //  print_r($this->progress);

    }

    /***
     * 推送上传状态
     */
    public function progress(){
        if(!empty($_SESSION[$this->progress]))
        {
            $aData = $_SESSION[$this->progress];
            $iProcessed = $aData['bytes_processed'];
            $iLength    = $aData['content_length'];
            $iProgress  = ceil(100*$iProcessed / $iLength);
        }
        else
        {
            $iProgress = 100;
        }

        return $iProgress;

    }
    public  function logging($error){

        @preg_match_all("{[0-9]*\d}",$this->NO[0],$NO);
        @$NO=$NO[0][0];
        $mediaID=md5(time().$this->name);
        $DB=new DB("mail");
        $time=time();
        @$sql="insert into addAttachment(NO,mediaID,create_at,operator,errMsg,status) values('$NO','$mediaID','$time','$this->operator','$error',0)";
        if($NO!=""){
            $DB->Query($sql);

        }

    }




    public function error($error){
        if($error==40001){
            $data=array(
                'code'=>40001,
                'msg'=>"error:No file selected!",

            );
        }else if($error==40002){
            $data=array(
                'code'=>40002,
                'msg'=>$this->error
            );
        }else if($error==40003){
            $data=array(
                'code'=>40003,
                'msg'=>"error:The file type is not allowed to upload!",

            );

        }

        else if($error==40004){
            $data=array(
                'code'=>40004,
                'msg'=>"error:The file size must be no more than ".$this->allowFileSize."B!",

            );

        }else if($error==0){
            $data=array(
                'code'=>0,
                'msg'=>"OK!",
                'mediaID'=>md5(time().$this->name),
                'data'=>[$this->fileData]

            );
        }


        echo json_encode($data);
        $this->logging($error);

    }

}






?>