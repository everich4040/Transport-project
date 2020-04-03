<?php


class Messagify{
    public $res=[];

    function __construct(){
        
        $this->res['msg']='';
        $this->res['err']='';
        $this->res['redirect']='';
        
    }



    public function msg($message)
    {

        $this->set('msg',$message);
        return json_encode($this->res);

    }

    public function err($error)
    {

        $this->set('err',$error);
        return json_encode($this->res);
    }

    public function set($key,$val)
    {
        $this->res[$key]=htmlentities($val);
    }

    public function flush()
    {   

            echo json_encode($this->res);
            die();
    }
    
}
