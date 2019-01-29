<?php
namespace admin\controller;
use framework\core\Controller;
use framework\core\Factory;
/**
 * 话题模块; 主要负责后台话题的添加、删除、修改、查询等
 */
class TopicController extends Controller
{
    public function indexAction(){
        return $this->smarty->display('Topic/index.html');
    }

    public function addAction(){
        return $this->smarty->display('Topic/add.html');
    }

    public function addHandleAction(){

    }

    public function editAction(){
        return $this->smarty->display('Topic/edit.html');
    }

    public function updateAction(){

    }

    public function deleteAction(){
        
    }
}