<?php
namespace framework\core;

class Framework
{
    //在构造方法中初始化
    public function __construct(){
        //定义路径常量
        $this->initConst();
        
        $this->autoload();
        //加载配置文件
        $FWcon = $this->loadFrameworkConfig();
        $APPcon = $this->loadCommonConfig();
        $GLOBALS['config'] = array_merge($FWcon, $APPcon);
        $this->initMCA();
        $Mcon = $this->loadModuleConfig();
        $GLOBALS['config'] = array_merge($GLOBALS['config'], $Mcon);
        $this->dispatch();
    }

    //初始化路径
    private function initConst(){
        //getcwd(); 获得php文件的工作目录
        define('ROOT_PATH', str_replace('\\', '/', getcwd().'/'));
        define('APP_PATH', ROOT_PATH.'application/');
        define('FRAMEWORK_PATH', ROOT_PATH.'framework/');
        //公共资源目录
        define('PUBLIC_PATH', './public/');
        /**
         * '/'根目录-》从服务器根目录开始:hotdoc；是指apache指定的根目录。
         * './'根目录-》是指从当前文件出发位置同级目录。
         * '../'上层目录-》是指当前文件所在上层目录。
         */
        //上传目录
        define('UPLOAD_PATH', 'uploads/');
        //缩略图保存目录
        define('THUMB', 'thumb/');
    }

    //注册自动加载
    protected function autoload(){
        //如果一个函数的参数是回调函数，就直接写函数名
        //如果函数的参数是一个对象的方法，需要传递一个数组进去，参数1就是对象，参数2就是对象方法
        spl_autoload_register(array($this, "autoloder"));
    }
    //自动加载执行的函数
    protected function autoloder($className){
        //echo '我们需要：'.$className.'<br/>';
        //对第三方类做特殊处理，手动加载；
        if ($className == 'Smarty'){
            require_once FRAMEWORK_PATH.'vendar/smarty/Smarty.class.php';            
            return;
        }
        //1.现将带有命名空间的类，用\分割为数组
        $arr = explode('\\', $className);        
        //2.根据第一个元素确定根目录
        if ($arr[0] == 'framework'){
            $basic_path = ROOT_PATH;
        }else{
            $basic_path = APP_PATH;
        }

        //3.确定framework或application下的子目录
        $sub_path = str_replace('\\', '/', $className);
        //4. 确定文件名和后缀
        //确定后缀：类文件的后缀是.class.php；接口文件的后缀是.interface.php
        //framework\dao\I_ADO,判断最后元素是否是I_开头的，如果是I_开头的就是接口。
        if (substr($arr[count($arr)-1], 0, 2) == 'I_') {
            $fix = '.interface.php';
        } else {
            $fix = '.class.php';
        }
        $class_file = $basic_path.$sub_path.$fix;
        //5. 先加载控制器类
        //如果不是按照我们定义的命名空间规则，不是需要加载的类。则不加载这个文件
        if (file_exists($class_file)){
            require_once $class_file;
        }
        
    }

    //确定mca
    protected function initMCA(){
        $m = isset($_GET['m']) ? $_GET['m'] : $GLOBALS['config']['default_module'];
        define('MODEL', $m);
        $c = isset($_GET['c']) ? $_GET['c'] : $GLOBALS['config']['default_controller'];
        define('CONTROLLER', $c);
        $a = isset($_GET['a']) ? $_GET['a'] : $GLOBALS['config']['default_action'];
        define('ACTION', $a);

    }

    //实例化对象在调用方法
    protected function dispatch(){
        $controller_name = MODEL.'\controller\\'.CONTROLLER.'Controller';
        //再实例化对象,实例化时类不存在出发自动加载函数
        $controller = new $controller_name;
        $a = ACTION;
        $controller->$a();
    }

    //加载配置文件
    protected function loadFrameworkConfig(){
        return require_once FRAMEWORK_PATH.'config/config.php';
    }

    private function loadCommonConfig(){
        $config_file = APP_PATH.'common/config/config.php';
        if (file_exists($config_file)){
            return require_once $config_file;
        }else{
            return array();
        }
        
    }
    private function loadModuleConfig(){
        $config_file = APP_PATH.MODEL.'/config/config.php';
        if (file_exists($config_file)){
            return require_once $config_file;
        }else{
            return array();
        }
    }

}