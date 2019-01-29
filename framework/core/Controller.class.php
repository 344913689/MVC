<?php
namespace framework\core;
/**
 * 控制器基类
 */
class Controller
{
    protected $smarty;

    public function __construct(){
        $this->initTimezone();
        $this->initView();        
    }
    
    public function initView(){
        //初始化视图类
        $this->smarty = new \Smarty();
        $this->smarty -> left_delimiter = '<{';
        $this->smarty -> right_delimiter = '}>';
        $this->smarty -> setTemplateDir(APP_PATH.MODEL.'/view/');
        $this->smarty -> setCompileDir(APP_PATH.'runtime/');
    }

    public function initTimezone(){
        //初始化时区
        date_default_timezone_set('PRC');
    }
    
    protected function jump($url, $message, $delay = 3){
        header("Refresh:$delay; url = $url");
        echo $message;
        return false;
    }

    protected function jsonReturn ($code ,$message, $data){
        $json['code'] = $code;
        $json['message'] = $message;
        $json['data'] = $data;
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($json);
        exit();
    }
    
}