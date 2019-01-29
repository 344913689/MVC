<?php
namespace framework\tools;

// $file = new Upload();
// $fileInfo = $file->uploadFile('fm');
// if (is_int($fileInfo)) {
//     $file->errornumber();
// }
// print_r($fileInfo);

class Upload{

    //文件上传路径
    public $path = './upload/';
    //允许上传的文件后缀
    protected $allowSuffix = ['jpg', 'jpeg', 'png', 'gif', 'wbmg'];
    //允许上传文件的mime
    protected $allowMime = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/wbmg'];
    //允许上传的文件大小
    protected $maxSize = 2000000;
    //是否启用随机名称
    protected $isRandName = true;
    //是否加上文件前缀
    protected $preFix = 'up_';


    //错误号码和错误信息
    protected $errorNumber;
    protected $errorInfo;

    //文件的信息
    protected $oldName;
    protected $suffix;
    protected $size;
    protected $mime;
    protected $tmpName;

    //文件新名字
    protected $newName;

    public function __construct($arr = []){
        foreach ($arr as $key => $value) {
            $this->setOption($key, $value);
        }
    }

    //判断$key是不是我的成员属相，如果是就赋值。
    protected function setOption($key, $value){
        $keys = array_keys(get_class_vars(__CLASS__));
        if (in_array($key,$keys)) {
            $this->key = $value;
        }

    }

    public function uploadFile($key){
        //$key 就是input的name值
        //判断有没有设置路径 path
        if (empty($this->path)) {
            $this->setOption('errorNumber', -1);
            return false;
        }        
        //判断该路径是否存在，是否可写
        if (!$this->check()) {
            $this->setOption('errorNumber', -2);
            return false;
        }
       //判断$_FILES里面的error信息是否为0，如果为0说明文件信息在服务器端可以直接获取，提取信息保存到成员属性
        if ($key['error']) {
            $this->setOption('errorNumber', $error);
            return false;
        } else {
            $this->getFileInfo($key);
        }
        //判断文件的大小大小、mime、后缀是否符合
        if (!$this->checkSize() || !$this->checkMime() || !$this->checkSuffix()) {
            return false;
        }
        //得到新的文件名字，是否启用随机名称
        $this->newName = $this->createNewName();
        //判断是否是上传文件，并且移动上传
        if (is_uploaded_file($this->tmpName)) {
            $moveFile = move_uploaded_file($this->tmpName, $this->path.$this->newName);
            if ($moveFile) {
                return $this->path.$this->newName;
            } else {
                $this->setOption('errorNumber', -7);
                return false;
            }
        } else {
            $this->setOption('errorNumber', -6);
            return false;
        }
        //
    }

    protected function check(){
        if (!file_exists($this->path) || !is_dir($this->path)) {
            return mkdir($this->path, 0777, true);
        }

        if (!is_writeable($this->path)) {
            return chmod($this->path, 0777);
        }

        return true;
    }

    protected function getFileInfo($key){
        $this->oldName = $key['name'];
        $this->mime = $key['type'];
        $this->tmpName = $key['tmp_name'];
        $this->size = $key['size'];
        $this->suffix = pathinfo($this->oldName)['extension'];
        $this->errorInfo = $key['error'];
    }

    protected function checkSize(){
        if ($this->size > $this->maxSize) {
            $this->setOption('errorNumber', -3);
            return false;
        }
        return true;
    }

    protected function checkMime(){
        if (!in_array($this->mime, $this->allowMime)) {
            $this->setOption('errorNumber', -4);
            return false;
        }
        return true;
    }

    protected function checkSuffix(){
        if (!in_array($this->suffix, $this->allowSuffix)) {
            $this->setOption('errorNumber', -5);
            return false;
        }
        return true;

    }

    protected function createNewName(){
        if ($this->isRandName) {
            $name = $this->preFix.uniqid().'.'.$this->suffix;
        } else {
            $name = $this->preFix.$this->oldName;
        }
        return $name;
    }

    public function __get($name){
        if ($name == 'errorNumber') {
            return $this->errorNumber;
        } else {
            return $this->getErrorInfo();
        }
    }

    protected function getErrorInfo(){
        switch($this->errorNumber){
            case -1:
                $msg = "文件路径没有设置。";
                break;
            case -2:
                $msg = "不是目录或者没有权限。";
                break;
            case -3:
                $msg = "文件太大，请重新上传不超过2M的文件。";
                break;
            case -4:
                $msg = "文件类型不符合要求。";
                break;
            case -5:
                $msg = "文件后缀不符合。";
                break;
            case -6:
                $msg = "文件来源不符合要求。";
                break;
            case -2:
                $msg = "文件移动失败。";
                break;
            default:
                $msg = $this->$errorInfo;
        }
    }

}