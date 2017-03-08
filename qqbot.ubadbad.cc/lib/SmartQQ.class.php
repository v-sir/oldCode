<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2016/8/11
 * Time: 16:52
 */
header("Content-type:text/html;charset=utf-8");
class SmartQQ{

    public $url;
    public $redirect_url;
    public function __construct(){
      //  $this->url="https://ssl.ptlogin2.qq.com/ptqrshow?appid=501004106&e=0&l=M&s=5&d=72&v=4&t=0.9463507030369263";
      //  $this->getQRimg($this->url);
       // while (1){
          //  $code=$this->CheckLogin("https://ssl.ptlogin2.qq.com/ptqrlogin?webqq_type=10&remember_uin=1&login2qq=1&aid=501004106&u1=http%3A%2F%2Fw.qq.com%2Fproxy.html%3Flogin2qq%3D1%26webqq_type%3D10&ptredirect=0&ptlang=2052&daid=164&from_ui=1&pttype=1&dumy=&fp=loginerroralert&action=0-0-26340&mibao_css=m_webqq&t=undefined&g=1&js_type=0&js_ver=10167&login_sig=&pt_randsalt=0");
       //     if($code==0){
       //         break;
        //    }
        //    else if($code==65){
         //       $this->getQRimg($this->url);
           // }
      //  }


    }

    /**
     * @param $url
     */
    public function getQRimg($url){
        $cookie_jar=dirname(dirname(__FILE__))."/chache/SmartQQ.txt";
        $httpInfo=$this->http($url,$cookie_jar);
        $filecontent=$httpInfo["body"];
        $filename="qr.jpg";
        $dir=dirname(dirname(__FILE__))."/chache/";
        if(!is_dir($dir)){
            mkdir($dir,0777);
        }
        $local_file = fopen($dir=dirname(dirname(__FILE__))."/chache/".$filename, 'w'                                                                                        );
        if (false !== $local_file){
            if (false !== fwrite($local_file, $filecontent)) {
                fclose($local_file);
            }
        }
        echo "<img src='./chache/qr.jpg'>";





    }
    /*66:未失效 65:已失效  67:二维码认证中 0:登录成功*/
    /**
     * @param $url
     * @return mixed
     */
    public function CheckLogin($url){

        $cookie_jar=dirname(dirname(__FILE__))."/chache/SmartQQ.txt";
        $referer="https://ui.ptlogin2.qq.com/cgi-bin/login?daid=164&target=self&style=16&mibao_css=m_webqq&appid=501004106&enable_qlogin=0&no_verifyimg=1&s_url=http%3A%2F%2Fw.qq.com%2Fproxy.html&f_url=loginerroralert&strong_login=1&login_state=10&t=20131024001";
        $httpInfo=$this->http($url,$cookie_jar,0,'',$referer,$is_redirect=0);
        $status=$httpInfo["header"]["http_code"];
        $httpInfo=$httpInfo["body"];
        //preg_match('/\d+/',$httpInfo,$data);
        preg_match_all("/'([^\']*)'/",$httpInfo,$data);
        $this->redirect_url=$data[1][2];
        $code=$data[1][0];
        if($status==200){
            switch($code){
                case 0:





                    $response=array(
                        'code'=>0,
                        'msg'=>'OK!',
                        'redirect_url'=>$this->redirect_url

                );
                    echo  json_encode($response);
                    sleep(2);
                    $this->login_redirect($this->redirect_url);
                    break;
                case 66: $response=array(
                    'code'=>66,
                    'msg'=>'Unexpired qrcode!'

                );
                    echo  json_encode($response);
                    break;
                case 65:  $response=array(
                    'code'=>65,
                    'msg'=>'Expired qrcode!'

                );
                    echo  json_encode($response);
                    break;
                case 67: $response=array(
                    'code'=>67,
                    'msg'=>'Try login...!'

                );
                    echo  json_encode($response);
                    break;
                case 7: $response=array(
                    'code'=>7,
                    'msg'=>'Invalid parameter!'

                );
                    echo  json_encode($response);
                    break;

                default :
                    $response=array(
                        'code'=>4,
                        'msg'=>'Unknown error!'

                    );
                    echo  json_encode($response);

            }



        }else{
            $response=array(
                'code'=>3,
                'msg'=>'Network failure!'

            );
            echo  json_encode($response);

        }
        return $code;









    }

    /**
     * @return mixed
     */
    public function getPtwebqq(){
        $cookie_jar=dirname(dirname(__FILE__))."/chache/SmartQQ.txt";
        $string=file_get_contents($cookie_jar);
        preg_match_all("{[\d]([^\d].+)[\s]}",$string,$data);
        $string= $data[0][12];
        preg_match("{[q]([^q][^\s].+)}",$string,$data);
        return $ptwebqq=$data[1];
      //  print_r($data);

    }
    public function getVfwebqq(){
        $cookie_jar=dirname(dirname(__FILE__))."/chache/SmartQQ.txt";
        $url="http://s.web2.qq.com/api/getvfwebqq";
        $referer="http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1";
        $postdata=array(
            'ptwebqq'=>$this->getPtwebqq(),
            'clientid'=>53999199,
            'psessionid'=>'',
            't'=>time()
        );
        $postdata=json_encode($postdata);
        $httpInfo=$this->http($url,$cookie_jar,0,$postdata,$referer,$is_redirect=0);
        echo  $httpInfo=$httpInfo["body"];



    }

    /**
     * @param $url
     */
    public function login_redirect($url){
        $cookie_jar=dirname(dirname(__FILE__))."/chache/SmartQQ.txt";
        $referer="https://ui.ptlogin2.qq.com/cgi-bin/login?daid=164&target=self&style=16&mibao_css=m_webqq&appid=501004106&enable_qlogin=0&no_verifyimg=1&s_url=http%3A%2F%2Fw.qq.com%2Fproxy.html&f_url=loginerroralert&strong_login=1&login_state=10&t=20131024001";
        $httpInfo=$this->http($url,$cookie_jar,0,'',$referer,$is_redirect=0);
        $referer=$url;
        $url="http://w.qq.com/proxy.html?login2qq=1&webqq_type=10";
        $httpInfo=$this->http($url,$cookie_jar,0,'',$referer,$is_redirect=0);

        //try second login
        $this->getVfwebqq();
        $url="http://d1.web2.qq.com/channel/login2";
        $referer="d1.web2.qq.com/proxy.html?v=20151105001&callback=1&id=2";
        $headerArr=array("Origin:http://d1.web2.qq.com");
        print_r($headerArr);
        $postdata=array(
            'ptwebqq'=>$this->getPtwebqq(),
            'clientid'=>53999199,
            'psessionid'=>'',
            'status'=>'online'
        );
        $postdata=http_build_query($postdata);
        $httpInfo=$this->http($url,$cookie_jar,1,$postdata,$referer,$is_redirect=0,$headerArr);
      echo  $httpInfo=$httpInfo["body"];




    }


    /**
     * @param $url
     * @param string $cookie_jar
     * @param int $is_post
     * @param string $postdata
     * @param string $referer
     * @param int $is_redirect
     * @param string $headerArr
     * @return array
     * 
     */
    public function http($url,$cookie_jar='',$is_post=0,$postdata='',$referer='http://www.qq.com/',$is_redirect=0,$headerArr=''){
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36");
        curl_setopt($ch, CURLOPT_POST,$is_post);
        if($postdata!=''){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArr);
        }

        curl_setopt($ch, CURLOPT_HEADER, 0);
      //  curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
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

//$test=new SmartQQ();




?>