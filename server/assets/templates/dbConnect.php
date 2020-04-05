<?php   
    include_once $_SERVER['DOCUMENT_ROOT'].'/server_config/config.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/templates/db.php';
    
    $db=new Db( DB_HOST ,   DB_NAME ,   DB_USER ,   DB_PASS);

    if($db->conn === null || $db->err !== null){
        include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/messagify.php';
        $msg=new Messagify();
        $msg->err('unable to connect database ');
        $msg->msg(htmlentities($db->err));
        $msg->flush();
    }
    
