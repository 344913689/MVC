<?php
namespace framework\dao;
use framework\dao\I_DAO;
use framework\core\Factory;
use \PDO;
use \PDOException;

//首先，类要实现，完成接口规定内容implements I_DAO
class DAOPDO
{
    //私有的属性，将来实例化的对象保存到该属性上
    private static $instance; //DAOPDO类的单例对象
    private $pdo; //PDO对象

    private function __construct($option){
        //构造方法中，初始化操作（链接数据库）
        $host = isset($option['host']) ? $option['host'] : '';
        $user = isset($option['user']) ? $option['user'] : '';
        $pass = isset($option['pass']) ? $option['pass'] : '';
        $dbname = isset($option['dbname']) ? $option['dbname'] : '';
        $port = isset($option['port']) ? $option['port'] : '';
        $charset = isset($option['charset']) ? $option['charset'] : 'UTF8';

        $dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=$charset";

        try {
            $this->pdo = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getSingleton(){
        return Factory::M('DAOPDO');
    }
}