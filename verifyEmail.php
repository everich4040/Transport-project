<?php

    
    include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/formValidator.php';
    
    
    if(!validateRequest(array('email','id','key'))){
        #############
        ## PRINT FAILED HTML VALIDATION
        chdir('templates');
        include_once $_SERVER['DOCUMENT_ROOT'].'/templates/emailNotVerified.html'; 
        die();
    } 
    
    
    
    include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/templates/dbConnect.php';
    
    $sql='SELECT `hash` FROM `activation` WHERE `user_id` = :ID';
    $result=$db->query($sql,array(':ID'=>$_REQUEST['id']));
    
    if(!$result){
        
        #############
        ## PRINT FAILED HTML VALIDATION
        chdir('templates');
        include_once $_SERVER['DOCUMENT_ROOT'].'/templates/emailNotVerified.html'; 
        die();
    
    }
    
    if( $result[0]['hash'] == $_REQUEST['key'] ){
        
        
        $db->conn->beginTransaction();
        $sql='DELETE FROM `activation` WHERE `user_id` = :id';

        if(!$db->exec($sql,array(':id'=>$_REQUEST['id']))){
            $db->conn->rollBack();
            chdir('templates');
            include_once $_SERVER['DOCUMENT_ROOT'].'/templates/emailNotVerified.html'; 
            echo 'something went wrong please try again!';
            die();
        }



        $sql='UPDATE `users` SET `activated` = "1" WHERE `user_id` = :id AND `email` =:email;';

        if(!$db->exec($sql,array(':id'=>$_REQUEST['id'],':email'=>$_REQUEST['email']))){
            $db->conn->rollBack();
            chdir('templates');
            include_once $_SERVER['DOCUMENT_ROOT'].'/templates/emailNotVerified.html'; 
            echo 'something went wrong please try again!';
            die();
        }



        $db->conn->commit();
        chdir('templates');
        include_once $_SERVER['DOCUMENT_ROOT'].'/templates/emailVerified.html';         
        die();
    }
    
    chdir('templates');
    include_once $_SERVER['DOCUMENT_ROOT'].'/templates/emailNotVerified.html'; 
    echo 'failed verifying key';
    die();
