<?php

$code = new Code();
$code->outImage();


class Code{

    //验证码位数
    protected $number;
    //验证码类型0纯数字，1纯字符，2数字字符混合
    protected $codeType;
    //验证码图片宽度
    protected $width;
    //验证码图片高度
    protected $height;
    //验证码图片
    protected $image;
    //验证码字符串
    protected $code;

    public function __construct(int $number = 4, int $type = 2, int $width = 100, int $height = 30){
        //初始化
        $this->number = $number;
        $this->codeType = $type;
        $this->width = $width;
        $this->height = $height;
        //生成验证码
        $this->code = $this->createdCode();

    }

    protected function createdCode(){

        //通过验证码类型生成不同的验证码
        switch($this->codeType){
            case 0:
                $code = $this->getNumberCode();
                break;
            case 1:
                $code = $this->getCharCode();
                break;
            case 2:
                $code = $this->getNumberCharCode();
                break;
            default:
                $code = $this->getNumberCharCode();
                break;
        }
        return $code;

    }

    public function __destruct(){
        imagedestroy($this->image);
    }

    public function __get($name){
        if ($name == 'code') {
            return $this->code;
        }
        return false;
    }

    protected function getNumberCode(){

        $str = join('',range(0,9));
        return substr(str_shuffle($str), 0, $this->number);
    }

    protected function getCharCode(){
        $str = join('', range('a', 'z'));
        $str = $str.strtoupper($str);
        return substr(str_shuffle($str),  0, $this->number);
    }

    protected function getNumberCharCode(){
        $str = '';
        $strn = join('', range(0, 9));
        $strc = join('', range('a', 'z'));
        $str = $strn.$strc.strtoupper($str);
        return substr(str_shuffle($str), 0 , $this->number);
    }

    protected function createImage(){
        $this->image = imagecreatetruecolor($this->width, $this->height);
    }

    protected function fillBack(){
        imagefill($this->image, 0, 0, $this->lightColor());
    }

    protected function lightColor(){
        return imagecolorallocate($this->image, mt_rand(130, 255), mt_rand(130, 255), mt_rand(130, 255));
    }

    protected function darkColor(){
        return imagecolorallocate($this->image, mt_rand(0, 120), mt_rand(0, 120), mt_rand(0, 120));
    }

    protected function drawChar(){
        $width = ceil($this->width / $this->number);
        for ($i=0; $i < $this->number; $i++) {
            $x = mt_rand($i * $width + 5, ($i + 1) * $width -10);
            $y = mt_rand(0, $this->height - 15);
            imagechar($this->image, 5, $x, $y, $this->code[$i], $this->darkColor());
        }
    }

    protected function darwDisturb(){
        for ($i=0; $i < 150; $i++) {
            $x = mt_rand(0, $this->width);
            $y = mt_rand(0, $this->height);
            imagesetpixel($this->image, $x, $y, $this->lightColor());
        }
    }

    protected function show(){
        ob_clean();
        header('Content-Type: image/png');    
        imagepng($this->image);
    }

    public function outImage(){

        //创建画布
        $this->createImage();
        //填充背景色
        $this->fillBack();
        //将验证码字符串画到画布中
        $this->drawChar();
        //添加干扰元素
        $this->darwDisturb();
        //输出并显示
        $this->show();
    }
}
