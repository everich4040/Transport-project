<?php

header ('Access-Control-Allow-Origin: none');
header('Content-Type: applicaton/json');

define('ROOT',$_SERVER['DOCUMENT_ROOT'],1);



include_once ROOT.'/server/assets/messagify.php';

$res=new Messagify();

###### checkup of action (signup / login / reset) ###############################
    
    if(isset($_REQUEST['action'])){
        
        
        #redirect on action        
        
        if(preg_match('#sign(.{0,})up#i',$_REQUEST['action'])){

            handleSignup();
            exit();

        } else if (preg_match('#log(.{0,})in#i',$_REQUEST['action'])){
            
            handleLogin();
            exit();
        
        }



        $res->err('no valid (signup / login) action variable is specified');
        $res->flush();
        exit();

    } else {
        #respond with errors
        $res->err('no valid (signup / login) action variable is specified ');
        $res->flush();
        die();
    }



    $res->err('no valid (signup / login) action variable is specified ');
    $res->flush();
    die();


##################################################################################





































    function handleSignup(){
        global $res;

        include_once ROOT.'/server/assets/formValidator.php';
        if( validateRequest(array('fullName','email','password','confirmPassword','phone')) ){
            
            ##################################################
            ########    VALIDATION STUFFS :)        ##########
            ##################################################

                if(!validateEmail($_REQUEST['email'])){
                    
                    $res->err('Email Id is invalid');
                    $res->flush();  
                    exit();
                    
                }
                
                if(!validateName($_REQUEST['fullName'])){
                    
                    $res->err('user name must be greater than 2 characters');
                    $res->flush();  
                    exit();
                    
                }
                
                
                if(!validatePhoneNumber($_REQUEST['phone'])){
                    
                    $res->err('phone number is not valid');
                    $res->flush();  
                    exit();
                }
                
                if(!($_POST['password'] == $_POST['confirmPassword'])){
                    
                    $res->err('Conformation Password doesnt match');
                    $res->flush();  
                    exit();

                }

            ##################################################
            ########    REAL DATABASE STUFFS :)     ##########
            ##################################################
            
            #################################################
            #########   SQL STUFFS ##########################
            
                include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/templates/dbConnect.php';
                
                $name=$_REQUEST['fullName'];
                $email=$_REQUEST['email'];
                $phone=$_REQUEST['phone'];        
                

                ##  SOME VALIDATIONS    
                
                
                
                $psswd=password_hash($_REQUEST['password'] , PASSWORD_DEFAULT);
            
            
            
            
            
            #################################################


            

            $res->msg('Signup Successfull');
            $res->flush();
            exit();
            #SUCCESSFULLY EXIT SIGNUP
        } else {
            
            $res->err('please fill out the form properly');
            $res->msg('some values are empty or missing');
            $res->flush();  
            exit();
            #FAILED SIGNUP
        }

    }


#########################################################################




####################    HANDLE LOGIN    ###############################    

    function handleLogin()
    {

        echo 'logIn';

    }


########################################################################