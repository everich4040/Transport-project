<?php
    
    include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/templates/dbConnect.php';



### CHECK FOR ANY ERROR

    if($db->err){

        echo $db->err;
        die();

    }
#################################


############################### users Table
    $sql='  CREATE TABLE IF NOT EXISTS `users`( 
                `user_id` INT(10)  UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `name` VARCHAR(20)  NOT NULL ,
                `email` VARCHAR(35) NOT NULL ,
                `password` VARCHAR(225) NOT NULL ,
                `phone_number` INT(16)  UNSIGNED NOT NULL,
                `activated` INT(1) NOT NULL DEFAULT "0",
                `register_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            );
    ';

    if($db->execute($sql)){
        echo 'table `users` created! <br>';
       
    } else {
        
        echo 'Failed Creating Table `users` <br><br>';
        echo $db->err.'<br><br><br>';
    } 
     
##########################################################
 

########################    activation Table
    $sql='CREATE TABLE IF NOT EXISTS `activation`(
        `user_id` INT(10)  UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `hash` VARCHAR(40) /*sha1 hash*/
    );';    

    
    if($db->execute($sql)){
        echo 'table `activation` created! <br>';
    
    } else {
        
        echo 'Failed Creating Table `activation` <br><br>';
        echo $db->err.'<br><br><br>';
    } 
    
###########################################################



