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


    }