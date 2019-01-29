<?php
namespace common\command;

class ToJson
{
    public function __construct($code,$message="",$data=array()){  
        $result=array(  
            'code'=>$code,  
            'message'=>$message,  
            'data'=>$data   
        );  
        //输出json  
        echo json_encode($result);
    } 
}