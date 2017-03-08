<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2016/10/25
 * Time: 21:06
 */
$login=new mima();
//$login->login("xtdx","241000");
class mima{
    var $USERNAME;
    var $PASSWORD;
    var $RANDOMCODE;
    var $cookie_jar;
    public function __construct(){


        $url="http://qmer.iqingmei.com/account2016/login/";
        $referer="http://qmer.iqingmei.com/index2016/login";


        $dir=dirname(__FILE__)."/chache/";
        if(!is_dir($dir)){
            mkdir($dir,0777);
        }
        $this->cookie_jar=dirname(__FILE__)."/chache/mima.txt";
        $httpInfo=$this->http($url,$this->cookie_jar,0,'',$referer);

       $this->to_mimaServer();
    }
    public function to_mimaServer(){
        $url="http://qmer.iqingmei.com/article2016/upload/type/simg/sid/46/w/70/h/100/o/=";
        $referer="http://qmer.iqingmei.com/article2016/edit/id/52654";
        $postdata=array(
          // 'simg'=>'@'.dirname(dirname(__FILE__)).'/chache/460095.jpg',
           'simg'=> new CURLFile(realpath(dirname(dirname(__FILE__)))."/chache/460095.jpg"));

       $postdata=http_build_query($postdata);
        $httpInfo=$this->http($url,$this->cookie_jar,1,$postdata,$referer);
        echo $httpInfo=$httpInfo['body'];


    }

    public function login($USERNAME,$PASSWORD){
        $this->USERNAME=$USERNAME;
        $this->PASSWORD=$PASSWORD;
        //@$this->RANDOMCODE=$_GET['verify'];
        $verify=$this->auto_verify();
        $verify=json_decode($verify,true);
        $verify_code=$verify['code'];
        if($verify_code==0){
            $url="http://qmer.iqingmei.com/account2016/login";
            $referer="http://qmer.iqingmei.com/index2016/login";
            $postdata=array(
                'username'=>$this->USERNAME,
                'password'=>$this->PASSWORD,
                'code'=>$verify['idcode']
            );
           // print_r($postdata);
            $postdata=http_build_query($postdata);
            $httpInfo=$this->http($url,$this->cookie_jar,1,$postdata,$referer);
           echo $httpInfo=$httpInfo['body'];
           // if(preg_match('{<font color="red">(.+)</font>}',$httpInfo,$string)){
             ///   $data=array(
                //    'code'=>1,
                 //   'msg'=>"Wrong username or password!"
                //);
               // echo json_encode($data);

          //  }else{

              //  $this->getInfo();
           // }


        }else if($verify_code==-1){
            $data=array(
                'code'=>-1,
                'msg'=>'Verify fail!'

            );
            echo json_encode($data);
        }else if($verify_code==4){
            $data=array(
                'code'=>4,
                'msg'=>'Unknown mistake!'

            );
            echo json_encode($data);
        }


        //print_r($postdata);

    }
    public function auto_verify(){
        $url="http://qmer.iqingmei.com/account2016/checkcode/t/".time();
        $referer="http://qmer.iqingmei.com/index2016/login";
        $httpInfo=$this->http($url,$this->cookie_jar,0,'',$referer);
        $filename="mimaVerify.jpg";
        $filecontent= $httpInfo['body'];
        $local_file = fopen($dir=dirname(__FILE__)."/chache/".$filename, 'w'                                                                                        );
        if (false !== $local_file){
            if (false !== fwrite($local_file, $filecontent)) {
                fclose($local_file);
            }
        }
        $image_file = dirname(__FILE__)."/chache/".$filename;
        // $image_info = getimagesize($image_file);
        $base64_image_content = chunk_split(base64_encode(file_get_contents($image_file)));
        $url="http://api.sky31.com/idcode/confirm5.php";
        $postdata=array(
            'data'=>$base64_image_content
        );
        $postdata=http_build_query($postdata);
        $reInfo=$this->http($url,'',1,$postdata);
        // echo $reInfo['body'];
        // echo "<img src='./chache/verify.jpg'>";
        return $reInfo=$reInfo['body'];

    }
    public function http($url,$cookie_jar='',$is_post=0,$postdata='',$referer='http://qmer.iqingmei.com/index2016/login',$is_redirect=0,$headerArr='',$is_header=0){
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36");
        curl_setopt($ch, CURLOPT_POST,$is_post);
        if($postdata!=''){

            // curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArr);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        }

        curl_setopt($ch, CURLOPT_HEADER, $is_header);
        //  curl_setopt($ch, CURLOPT_NOBODY, 0);    //??body?
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt ($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie_jar);
        curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_jar);
        if($is_redirect==1){
            curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);

        }


        $body = curl_exec($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);
        return $httpInfo = array_merge(array('header' => $header), array('body' => $body));




    }
}















?>