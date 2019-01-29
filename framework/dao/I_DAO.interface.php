<?php
namespace framework\dao;

interface I_DAO
{
    //查询一条记录的方法
    public function fetchRow($sql);
    //查询所有记录的方法
    public function fetchAll($sql);
    //查询一个字段的值
    public function fetchColumn($sql);
    //执行增删改查操作
    public function exec($sql);
    //引号转译包裹的方法
    public function quote($sql);
    //查询刚插入的这条数据的主键
    public function lastInsertId($sql);
}