<?php
namespace admin\controller;
use framework\core\Controller;
use framework\tools\Upload;
use framework\core\Factory;
use admin\model\CategoryModel as categoryModel;
/**
 * 后台问题模块；负责后台增删改查分类操作
 */
class CategoryController extends Controller
{
   
    //显示问题列表
    public function indexAction(){
        $model = Factory::M('category');
        $cat_list = $model->select();
        if (!$cat_list) {
            return $this->jsonReturn(-1, '没有数据，请添加！', $cat_list);
            $datas['message'] = "没有数据，请添加！";
            $this->smarty->assign('datas', $datas);
        } else {
            return $this->jsonReturn(1, '收到数据', $cat_list);
            $this->smarty->assign('datas', $cat_list);
        }
        //return $this->smarty->display('category/index.html');
        
    }

    //添加内容列表
    public function addAction(){
        $model = Factory::M('category');
        $cat_list = $model->select();
        if ($cat_list) {
            //无限极递归查询
            $cat_list = $model->getTreeCategory($cat_list);
        } else {
            $cat_list = [];
        }
        $this->smarty->assign('datas', $cat_list);
        return $this->smarty->display('category/add.html');
    }

    //提交表单并接受表单的数据
    public function addHandleAction(){
        $model = Factory::M('category');
        //接收首先判断数据是否合法
        if(!$model->checkData($_POST)){
            $this->jump('?m=admin&c=category&a=addAction', $model->showError());
        }
        //exit();
        //先上传分类图标，返回文件地址之后和其他表单项一起保存到数据库
        $upload = new Upload();
        $upload->path = PUBLIC_PATH.UPLOAD_PATH.CONTROLLER.'/';
        $upload_info = $upload->uploadFile($_FILES['cat_logo']);
        if (is_int($upload_info)) {
            $file->errornumber();
            return false;
        }
        $data['cat_name'] = $_POST['cat_name'];
        $data['cat_desc'] = $_POST['cat_desc'];
        $data['cat_logo'] = $upload_info;
        $data['parent_id'] = $_POST['parent'];

        //插入数据库        
        $result = $model->insert($data);
        if ($result) {
            return $this->jump('?m=admin&c=category&a=indexAction', "添加成功");
        } else {
            return $this->jump('?m=admin&c=category&a=addAction', "添加失败");
        }

    }
    
    //显示编辑的表单
    public function editAction(){
        $datas = $_GET;
        $model = Factory::M('category');
        $cat_list = $model->where("cat_id={$datas['id']}")->select();
        $parent_list = $model->field(['cat_id', 'cat_name'])->where("cat_id={$cat_list[0]['parent_id']}")->select();
        if (!$parent_list) {
            $parent_list[0]['cat_id'] = '0';
            $parent_list[0]['cat_name'] = '顶级分类';
        }
        $this->smarty->assign('cat_list', $cat_list[0]);
        $this->smarty->assign('parent_list', $parent_list[0]);
        return $this->smarty->display('category/edit.html');
    }

    //提交表单并更新
    public function updateAction(){
        $datas = $_POST;
        $id = $datas['cat_id'];
        if ($_FILES['cat_logo']['error'] == 0) {
            $upload = new Upload();
            $upload->path = UPLOAD_PATH.CONTROLLER.'/';
            $upload_info = $upload->uploadFile($_FILES['cat_logo']);
            if (is_int($upload_info)) {
                $file->errornumber();
                return false;
            }           
            $datas['cat_logo'] = $upload_info;

            //删除旧图标（包括原图、缩略图）
            if (file_exists($datas['old_cat_logo'])) {
                unlink($datas['old_cat_logo']);
            }

        }
        unset($datas['old_cat_logo']);
        $model = Factory::M('category');
        $result = $model->where("cat_id={$id}")->update($datas);
        if ($result) {
            return $this->jump('?m=admin&c=category&a=indexAction', "更新成功");
        } else {
            return $this->jump('?m=admin&c=category&a=editAction&id='.$id, "更新失败");
        }
    
    }

    //删除删除分类
    public function deleteAction(){
        $cat_id['id'] = $_GET['id'];

        //只能删除叶子分类（有子分类的不能直接删除）
        //$sql = "select * from $this->true_table where parent_id = $datas['id']";
        $model = new categoryModel();
        $result = $model->isLeafCategory($cat_id['id']);
        if ($result) {
            return $this->jump('?m=admin&c=category&a=indexAction', "栏目还有附属，不能删除！");
        }

        //先删除相关资源
        $result = $model->where("cat_id={$cat_id['id']}")->field('cat_logo')->select();
        if ($result) {
            if (file_exists($result[0]['cat_logo'])) {
                unlink($result[0]['cat_logo']);
            }
        }
        //再删除记录
        $ret = $model->where("cat_id={$cat_id['id']}")->delete();
        if ($ret) {
            return $this->jump('?m=admin&c=category&a=indexAction', "删除成功");
        } else {
            return $this->jump('?m=admin&c=category&a=indexAction', "删除失败");
        }
    }
  

}