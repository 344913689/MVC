<?php
namespace framework\core;
/**
 * 工厂类，功能是根据用户传递的模型类，返回单例的模型对象。 
 */

class Factory
{
    //定义公共静态方法
    //参数：模型类名
    public static function M($modelName){
        //1. 先判断模型中是否含有Model关键字，例如UserModel
        if (substr($modelName, -5) != 'Model') {
            $modelName .= 'Model';
        }
        //2. 判断模型中是否含有命名空间
        if (!strrchr($modelName, '\\')) {
            //完善模型名为：admin\model\UserModel
            $modelName = MODEL.'\model\\'.$modelName;
        }

        //每次都是一个新的数组所以每次查询都是为空。         
        static $model_list = array();
        if (!isset($model_list[$modelName])){
            $model_list[$modelName] = new $modelName;
        }
        return $model_list[$modelName];
    }


}

