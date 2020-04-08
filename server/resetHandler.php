<?php

    header('Content-Type: applicaton/json');
    error_reporting(0);

    include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/messagify.php';
    $res=new Messagify();

    if( !isset($_REQUEST['email']) || !isset($_REQUEST['action']) || empty($_REQUEST['email']) ){
        $res->err('failed');
        $res->msg('something is wrong with form');
        $res->flush();    
        die();
    }


    ### WILL SEND EMAIL ABOUT RESET
    
    include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/templates/dbConnect.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/utils.php';
    
    $newHash=sha1(Util::randStr());


    ##check if user exsists
    $sql='SELECT * FROM `users` WHERE `email`=:email OR `phone_number`=:email';

    $result=$db->query($sql,array(':email'=>$_REQUEST['email']));

    if(count($result)<=0){
            
        $res->err('failed');
        $res->msg('please check your email');
        $res->flush();    
        die();

    }

    if(!isset($result[0]) || !isset($result[0]['user_id'])){
        $res->err('failed');
        $res->msg('please check your email');
        $res->flush();    
        die();
        
    }

    ##TO ADDRESS
    $to=$result[0]['email'];
    
    ## ID
    $uId=$result[0]['user_id'];
    
    $db->conn->beginTransaction();

    ##REMOVE OLD
    $sql='DELETE  FROM `reset` WHERE `user_id` = :uId';
    $r=$db->exec($sql,array(':uId'=>$uId));
    if(!$r){

        $db->conn->rollback();
        $res->err('failed');
        $res->msg('something unusual happened');
        $res->flush();    
        
        die();
    }

    #INSERT NEW
    $sql='INSERT INTO `reset`(`user_id`,`hash`) VALUE(:uId,:hash)';

    $r=$db->exec($sql,array(':uId'=>$uId,':hash'=>$newHash));

    if(!$r){

        $db->conn->rollback();
        $res->err('failed');
        $res->msg('something unusual happened');
        $res->flush();    
        
        die();
    }

    
    



    


    ##URL HERE
    $url=$_SERVER['HTTP_HOST'].'/reset_password.php?uid='.$uId.'&key='.$newHash;
    
    $from=$_SERVER['HTTP_HOST'];
    $subject = 'RESET YOUR PASSWORD'; 
    $headers = "From:  ".$from." \r\n";
    // $headers .= "Reply-To:  giri00421@outlook.com  \r\n";
    // $headers .= "CC: giri00421@outlook.com \r\n";
    
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html;charset=UTF-8 \r\n"; 

    $http = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';

    $html=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/resetMail.html');
    
    $html=preg_replace('#\{\{webSiteName\}\}#mi',$http.$_SERVER['SERVER_NAME'],$html);

    $html=preg_replace('#\{\{targetUrl\}\}#mi',$url,$html,1);

    $message = $html; 
    // echo $message;
    $errr=0;

    
    
    try{

        if(!mail($to, $subject, $message, $headers)){
            
            $db->conn->rollback();
            $res->err('failed');
            $res->msg('something went wrong');
            $res->flush();    
            die();
        
        }

    } catch(Exception $e){
        $err=1;
    }
    
    if($err){
        $db->conn->rollback();
        $res->err('failed');
        $res->msg('something went wrong');
        $res->flush();    
        die();
    }
    
    
    $db->conn->commit();
    $res->msg('we successfully sent you a email to reset your password');
    $res->flush();    
    die();
    

