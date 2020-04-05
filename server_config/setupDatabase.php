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
                `phone_number` VARCHAR(16) NOT NULL,
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
        `user_id` INT(10)  UNSIGNED NOT NULL PRIMARY KEY ,
        `hash` VARCHAR(40) /*sha1 hash*/,
        `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );';    

    
    if($db->execute($sql)){
        echo 'table `activation` created! <br>';
    
    } else {
        
        echo 'Failed Creating Table `activation` <br><br>';
        echo $db->err.'<br><br><br>';
    } 
    
###########################################################



###################     sessions table  ###########

        ################################################################
        #######     in cookie the hash , ip and user_id will be set ####
        #####       and also in this table                           ###
        ####                                                          ##
        ################################################################

        $sql='CREATE TABLE IF NOT EXISTS `sessions`(
            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT(10)  UNSIGNED NOT NULL ,
            `ip` VARCHAR(16) NOT NULL ,
            `hash` VARCHAR(40) NOT NULL /*sha1 hash*/,
            `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        );';    


        if($db->execute($sql)){
            echo 'table `sessions` created! <br>';

        } else {
            
            echo 'Failed Creating Table `sessions` <br><br>';
            echo $db->err.'<br><br><br>';
        } 


###################################################
