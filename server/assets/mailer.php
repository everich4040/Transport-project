
<?php 


    function mailer($to,$from,$url){ 

        $subject = 'VERIFY YOUR EMAIL'; 
        $headers = "From:  ".$from." \r\n";
        // $headers .= "Reply-To:  giri00421@outlook.com  \r\n";
        // $headers .= "CC: giri00421@outlook.com \r\n";
        
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html;charset=UTF-8 \r\n"; 

        $http = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';

        $html=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/msgInlined.html');
        
        $html=preg_replace('#\{\{webSiteName\}\}#mi',$http.$_SERVER['SERVER_NAME'],$html);

        $html=preg_replace('#\{\{targetUrl\}\}#mi',$url,$html,1);

        $message = $html; 
        // echo $message;
        $errr=0;
        try{

            if(!mail($to, $subject, $message, $headers)){
                return 0;
            }

        } catch(Exception $e){
            $err=1;
        }
        
        if($err){
            return 0;
        }
        return 1;

    }