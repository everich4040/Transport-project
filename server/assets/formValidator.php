<?php


    function validateEmail($email){
        
        $email=trim($email,' <>/');
        
        if(strlen($email) > 35){
            return 0;
        }
        
        if(preg_match('#.{2,}@.{1,}\..{1,}#',$email)){
            return 1;
        }
        
        return 0;
    }

    function validateName($name){
        
        $name=implode('',explode(' ',$name));
        
        if(strlen($name) >= 2  && strlen($name) <= 20){
            return 1;
        } 

        return 0;
    }

    function validateRequest($arr){
      
       for($i=0;$i<count($arr);$i++){

            if(!isset($_REQUEST[$arr[$i]]) || empty($_REQUEST[$arr[$i]])){
                return 0;
            }
       
        }

        return 1;
    }
    

    function validatePhoneNumber($number){
        
        if(strlen($number) < 10 && strlen($number) > 16){

            return 0;
        
        }
        
        if(is_numeric($number) == 1){
            return 1;
        }
        
        return 0;
    
    }