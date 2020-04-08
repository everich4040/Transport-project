<?php
    class Util{
        


        /**
         * function randstr
         *
         * @param int $len : length of string
         * @param string $charSet : charecters 
         *
         * @return string
         */
        
        public static function randStr($len=6,$charSet='abcdefghijklmnopqrstuvwxyxABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_')
        {
            $gen='';

            for($a=0;$a<$len;$a++){
                $gen.=$charSet[rand(0,strlen($charSet)-1)];
            }

            return $gen; 
        }

        public static function checkSessions($arr)
        {   
            $a=1;
            foreach ($arr as $key) {
               
               if(!isset($_SESSION[$key]) && empty($_SESSION[$key])){
                    $a = 0;
               }
                
            }


            return $a;
        }

        public static function checkCookies($arr)
        {   
            $a=1;
            foreach ($arr as $key) {
               
               if(!isset($_COOKIE[$key]) && empty($_COOKIE[$key])){
                    $a = 0;
               }
                
            }


            return $a;
        }

        public static function checkLogin()
        {
            if(isset($_COOKIE['key']) || isset($_SESSION['key'])){
                return 1;
            }

            return 0;
        }

        
    }