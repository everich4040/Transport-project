<?php

    class Db{

        
        public $conn=null;
        public $err=null;
        
        
        function __construct($host,$dbName,$user,$pass)
        {   
            
            try{
            
                $this->conn=new PDO('mysql:host='.$host.';dbname='.$dbName, $user, $pass);
                
                $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);

            } catch(PDOException $e){

                $this->err =$e->getMessage();
            
            }

        }


        public function execute($sql)
        {
            try{

                if($this->conn->exec($sql) === false){

                    return 0;
                
                }

                return 1;
            }catch(PDOExecption $e){

                $this->err=$e->getMessage();
                return 0;
            }
        }

        public function query($sql,$arr){
            $a=0;
            $res=[];
            
            try{
                
                $stmt=$this->conn->prepare($sql);

                $stmt->execute($arr);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $res=$stmt->fetchAll();

                
            } catch(PDOException $e){
                
                $err = $e->getMessage();        
                $a=1;

            }

            if ($a==1){
                return 0;
            } 
            
            return $res;
        
        }

        public function exec($sql,$arr){
            $err=0;
            $res=1;
            try{

                $stmt=$this->conn->prepare($sql);
                $stmt->execute($arr);
                
            } catch(PDOException $e){
                $err=1;
                $this->err=$e->getMessage();
            }
            if($err){
                return false;
            }

            return $res;
        
        }
    }
