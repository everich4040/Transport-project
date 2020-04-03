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

    }
