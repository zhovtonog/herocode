<?php
	header('Access-Control-Allow-Origin: *');  
    
    if($_GET['type'] == "sendCaptcha"){
        /*$captchID = array("id" => 1);
        $content = file_get_contents("http://www.heroeswm.ru/" . $_GET['url']);
        print_r(urlencode(base64_encode($content)));
        
        $postdata = array(
            'method'    => 'post', 
            'key'       => '4334617dda92599c68ce2d026debe65e', 
            'file'      => '',
            'phrase'	=> 0,
            'regsense'	=> 0,
            'min_len'	=> 6,
            'max_len'	=> 6,
            'language'	=> 0,
            
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,             "http://rucaptcha.com/in.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,     1);
        curl_setopt($ch, CURLOPT_TIMEOUT,             60);
        curl_setopt($ch, CURLOPT_POST,                 1);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,         $postdata);
        $result = curl_exec($ch);
        
        print_r($result);
        print_r(curl_errno($ch));*/
        $is_verbose = false;
        $sendhost = "rucaptcha.com";
        
        
        $body = file_get_contents("http://www.heroeswm.ru/" . $_GET['url']);
        
        $conttype="image/pjpeg";
        
        $boundary="---------FGf4Fh3fdjGQ148fdh";
    
        $content="--$boundary\r\n";
        $content.="Content-Disposition: form-data; name=\"method\"\r\n";
        $content.="\r\n";
        $content.="post\r\n";
        $content.="--$boundary\r\n";
        $content.="Content-Disposition: form-data; name=\"key\"\r\n";
        $content.="\r\n";
        $content.="4334617dda92599c68ce2d026debe65e\r\n";
        $content.="--$boundary\r\n";
        $content.="Content-Disposition: form-data; name=\"phrase\"\r\n";
        $content.="\r\n";
        $content.="0\r\n";
        $content.="--$boundary\r\n";
        $content.="Content-Disposition: form-data; name=\"regsense\"\r\n";
        $content.="\r\n";
        $content.="0\r\n";
        $content.="--$boundary\r\n";
        $content.="Content-Disposition: form-data; name=\"numeric\"\r\n";
        $content.="\r\n";
        $content.="0\r\n";
        $content.="--$boundary\r\n";
        $content.="Content-Disposition: form-data; name=\"min_len\"\r\n";
        $content.="\r\n";
        $content.="6\r\n";
        $content.="--$boundary\r\n";
        $content.="Content-Disposition: form-data; name=\"max_len\"\r\n";
        $content.="\r\n";
        $content.="6\r\n";
        $content.="--$boundary\r\n";
        $content.="Content-Disposition: form-data; name=\"language\"\r\n";
        $content.="\r\n";
        $content.="0\r\n";
        $content.="--$boundary\r\n";
        $content.="Content-Disposition: form-data; name=\"file\"; filename=\"capcha.$ext\"\r\n";
        $content.="Content-Type: $conttype\r\n";
        $content.="\r\n";
        $content.=$body."\r\n"; //тело файла
        $content.="--$boundary--";
        
        
        $poststr="POST http://$sendhost/in.php HTTP/1.0\r\n";
        $poststr.="Content-Type: multipart/form-data; boundary=$boundary\r\n";
        $poststr.="Host: $sendhost\r\n";
        $poststr.="Content-Length: ".strlen($content)."\r\n\r\n";
        $poststr.=$content;
        
        
        $fp=fsockopen($sendhost,80,$errno,$errstr,30);
        if ($fp!=false)
        {
            if ($is_verbose) echo "OK\n";
            if ($is_verbose) echo "sending request ".strlen($poststr)." bytes...";
            fputs($fp,$poststr);
            if ($is_verbose) echo "OK\n";
            if ($is_verbose) echo "getting response...";
            $resp="";
            while (!feof($fp)) $resp.=fgets($fp,1024);
            fclose($fp);
            $result=substr($resp,strpos($resp,"\r\n\r\n")+4);
            if ($is_verbose) echo "OK\n";
        }
        else 
        {
            if ($is_verbose) echo "could not connect to anti-captcha\n";
            if ($is_verbose) echo "socket error: $errno ( $errstr )\n";
            return false;
        }
        $ex = explode("|", $result);
        if($ex[0] == "OK"){
            $captchID = array("id" => $ex[1]);
            echo json_encode($captchID);
            exit;
        } 
       
        
    } else if($_GET['type'] == "getCaptcha"){
        $result = file_get_contents("http://rucaptcha.com/res.php?key=4334617dda92599c68ce2d026debe65e&action=get&id=" . $_GET['id']);
        $ex = explode("|", $result);
        if($ex[0] == "OK"){
            $captchCode = array("code" => $ex[1]);
            echo json_encode($captchCode);
            exit;
        } else {
            $captchCode = array("code" => "");
            echo json_encode($captchCode);
            exit;
        }
    } else if($_GET['type'] == "report"){
        $result = file_get_contents("http://rucaptcha.com/res.php?key=4334617dda92599c68ce2d026debe65e&action=reportbad&id=" . $_GET['id']);
        
    }


    
    
    
	
?>
