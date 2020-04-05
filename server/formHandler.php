<?php

session_start();

// header ('Access-Control-Allow-Origin: none');
// header('Content-Type: applicaton/json');

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



###################     HANDLE SIGNUP ###############################

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
                
                if(!($_REQUEST['password'] == $_REQUEST['confirmPassword'])){
                    
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
                
                $sql='SELECT * FROM `users` WHERE `email`=:email OR `phone_number`=:phone';
                
                $result=$db->query($sql,array(':email'=>$email,':phone'=>$phone));
                
                
                #### FINAL CHECK if user exists in database

                if($db->err){
                    $res->err('Signup Failed');
                    $res->msg($db->err);
                    $res->flush();
                    exit();
                }

                if($result == 0 || count($result) >=1){
                    
                    $res->err('Signup Failed');                    
                    $res->msg('something went wrong! \n try another email / phone number');
                    $res->flush();
                    exit();
                    
                }
                
                // $db->conn->beginTransacrion();

                $passwd=password_hash($_REQUEST['password'] , PASSWORD_DEFAULT);
                
                ##  begin transaction
                $db->conn->beginTransaction();

                $sql='INSERT INTO `users`(`name`,`email`,`password`,`phone_number`) 
                                    VALUE(:name,:email,:password,:phone);
                ';
                
                if(!$db->exec($sql,array(':name'=>$name,':email'=>$email,':password'=>$passwd,':phone'=>$phone))){
                    $res->err('Signup Failed');                    
                    $res->msg('something went wrong please try again later!');
                    $res->flush();
                    exit();
                }

                $insertId=$db->conn->lastInsertId();
                if((int)$insertId >=0 ){
                    include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/utils.php';
                    $hash=sha1(Util::randStr());

                    if  (   !$db->execute('INSERT INTO `activation`(`user_id`,`hash`) 
                                    VALUES("'.(int)$insertId.'","'.$hash.'");')
                    )
                    {
                            #something went wrong
                            $db->conn->rollBack();
                            $res->err('Signup Failed');                    
                            $res->msg('something went wrong please try again later!');
                            $res->flush();
                            exit();        
                    }

                    if(!mailUserHash($email,$hash,$insertId)){
                        #something went wrong
                        $db->conn->rollBack();
                        $res->err('Signup Failed');                    
                        $res->msg('something went wrong please try again later!');
                        $res->flush();
                        exit();
                    }

                } else {
                    $res->err('Signup Failed');                    
                    $res->msg('something went wrong please try again later!');
                    $res->flush();
                    exit();
                }

            
            
            #################################################


            
            $db->conn->commit();
            $res->msg('Signup Successfull ');
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

        global $res;
        include_once ROOT.'/server/assets/formValidator.php';


        
        include_once ROOT.'/server/assets/utils.php';
        
        if(Util::checkLogin()){
            $res->msg('Successfully Logged In');
            $res->redirect($_SERVER['HTTP_ORIGIN']);
            $res->flush();    
        }

        ////// maximum attempts
        if(!isset($_SESSION['mat'])){
            $_SESSION['mat'] = 0;
        } else {
            $_SESSION['mat'] = ((int) $_SESSION['mat']) +1;
            
            if( (int) $_SESSION['mat'] >=3 ){

                
                $res->err('login failed');
                $res->msg('Maximum attemts of tries reached');
                $res->flush();            
                die();
            }
        }
        
        if(!isset($_COOKIE['mat'])){
            
            //2 min break
            setcookie('mat',0,time() + 60 * 2 );

        } else {
            $_COOKIE['mat'] = ((int) $_COOKIE['mat']) +1;
            if((int)$_COOKIE['mat'] >=3 ){
    
                
                $res->err('login failed');
                $res->msg('Maximum attemts of tries reached');
                $res->flush();            
                die();
            
                
            }
        }

        /////// 


        if( !validateRequest(array('email','password')) ){
            $res->err('login failed');
            $res->msg('invalid form');
            $res->flush();            
        }    
        

        include_once ROOT.'/server/assets/templates/dbConnect.php';
        

        $sql='SELECT * FROM `users` WHERE `email`=:email OR `phone_number`=:email;';
        
        $result=$db->query($sql,array(':email'=>$_REQUEST['email']));
        
            
        if(!$result || !isset($result[0])){
            $res->err('login failed');
            $res->msg('no account found on this email');
            $res->redirect($_SERVER['HTTP_ORIGIN'].'/signup.html');
            $res->flush(); 
        }
        
        if($result[0]['activated'] == '0'){
            $res->err('login failed');
            $res->msg('please activate your email first \n check out your mailbox');
            $res->flush(); 

        }

        if(!$result || !isset($result[0]) || !isset($result[0]['password'])){   
            
            $res->err('login failed');
            $res->msg('incorrect email or password');
            $res->flush();
            
        }
        
        

        if(!password_verify($_REQUEST['password'],$result[0]['password'])){
            
            $res->err('login failed');
            $res->msg('incorrect email or password');
            $res->flush();
        }
        
        
        if(!loginUser($result[0],$db)){

            $res->err('login failed');
            $res->msg('something unusual happened');
            $res->flush();
            
        }


        
        $res->msg('Successfully Logged In');
        $res->redirect($_SERVER['HTTP_ORIGIN']);
        $res->flush();
    }


########################################################################










##################  mail function   ##########
    
    function mailUserHash($email,$hash,$id){
        
        
        ##  email just for fun
        $uri;
        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
            $uri = 'https://';
        } else {
            $uri = 'http://';
        }
        
        $uri .= $_SERVER['HTTP_HOST'];
        $uri.='/verifyEmail.php?id='.$id.'&email='.$email.'&key='.$hash;

        
        include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/mailer.php';       
        if(mailer($email,$_SERVER['HTTP_HOST'],$uri)){

            return 1;

        }


        return 0;
    }

#############################################




######################  login user (handles user login / sets session and cookies) ###########

    //// * everything from USERS TABLE  /////
    function loginUser($arr,$db)
    {
        include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/utils.php';
        
        ### SET SESSION
        
        $_SESSION['user_id']=$arr['user_id'];
        $_SESSION['user_email']=$arr['email'];
        $_SESSION['user_phone']=$arr['phone_number'];
        $_SESSION['user_register_date']=$arr['register_date'];

        
         ### CONFIGS
        include_once $_SERVER['DOCUMENT_ROOT'].'/server_config/config.php';
        
        ### SET COOKIES 
        
        setcookie('user_id',$arr['user_id'],time() +  COOKIE_MAX_LIFE , '/');
        setcookie('user_email',$arr['email'],time() + COOKIE_MAX_LIFE, '/');
        setcookie('user_phone',$arr['phone_number'],time() +  COOKIE_MAX_LIFE, '/');
        setcookie('user_register_date',$arr['register_date'],time() +  COOKIE_MAX_LIFE, '/');




        ## setup database

        $aa=-1;

        $ipAddr=$_SERVER['REMOTE_ADDR'];
        
        sessS:
        
        
        $sql='SELECT * FROM `sessions` WHERE `ip`=:ip AND `user_id`=:uId';

        $result=$db->query($sql,array(':ip'=>$ipAddr,':uId'=>$arr['user_id']));

        if(isset($result[0]) && isset($result[0]['id'])){
                
            $_SESSION['id']=$result[0]['id'];            
            setcookie('id',$result[0]['id'],time() +  COOKIE_MAX_LIFE, '/');
            
            $_SESSION['key']=$result[0]['hash'];            
            setcookie('key',$result[0]['hash'],time() +  COOKIE_MAX_LIFE, '/');
            ###update date
            $sql='UPDATE `sessions` SET `date` = CURRENT_TIMESTAMP  WHERE id=:tId';
            if(!$db->exec($sql,array(':tId'=>$result[0]['id']))){
        
                return 0;
            }

            return 1;
        
        } 

        
        $sql ='INSERT INTO `sessions`(`user_id`,`ip`,`hash`) VALUE(:uId,:ip,:hash);';

        $hash= sha1(Util::randStr());

        $result=$db->exec($sql,array(':uId'=>$arr['user_id'],':ip'=>$ipAddr,':hash'=>$hash));
        
        if(!$result){
        
            return 0;
        }

        $aa++;
        
        if($aa!=0){
            return 0;
        } else {
            goto sessS;
        }
        
        return 0;
    }

########################################################################################