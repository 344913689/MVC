<?php
namespace admin\Model;
use framework\core\Model;
use framework\core\Factory;
/**
 * 分类模型；操作ask_category分类表
 */

class CategoryModel extends Model
{
   protected $tableName = 'category';
   private $error = [];

   //递归查询所有分类信息
   //@cat_list: 查询数组
   //@parent_id: 查询那个分类下面的子类；默认值是0，是要查询顶级分类下的子类
   //@level: 分类的层级；默认层级是顶级分类0
   public function getTreeCategory($cat_list, $p_id= 0, $level = 0){
      static $arr = array();
      foreach ($cat_list as $k=>$v){
         //谁的parent_id = 0 ,就是0的子类
         if ($v['parent_id'] == $p_id) {
            $v['level'] =$level;
            $arr[] = $v;
            //继续查询第下一层子类
            $this->getTreeCategory($cat_list, $v['cat_id'], $level+1);
         }
      }
      return $arr;
   }

   /**
    * 查询某个分类是否是叶子分类
    * @$cat_id: 是待删除的分类id
    * @$sql = "select * from $this->true_table where parent_id = $datas['id']"
    */

   public function isLeafCategory($cat_id){
      $model = Factory::M('category');
      return $result = $model->field('cat_id')->where("parent_id={$cat_id}")->select();
   }
   /**
    * 数据验证
    * @$data 控制器传递的数据
    */
   public function checkData($data){
      //分类标题不能为空
      if ($data['cat_name']  == null){
         $this->error[] = '分类标题不能为空';
      }
      //分类标题不能为纯数字或数字开头
      if ((int)$data['cat_name'] != 0){
         $this->error[] = '分类标题不能为数字或数字开头';
      }
      //分类的标题和描述不能超过12个字符长度
      if (mb_strlen($data['cat_name']) > 15 || mb_strlen($data['cat_desc']) > 15){
         $this->error[] = '长度太长了，不能超过12个字符';
      }
      //一个分类下面不能重建相同的子类
      if ($this->hasCategory($data['cat_name'], $data['parent'])){
         $this->error[] = '该分类已经存在';
      }
      return empty($this->error);

   }
   /**
    * 分类不能重复
    * @$data： 添加的分了标题；
    * @$id: 父类id;
    */
   protected function hasCategory($name, $p_id){
      $model = Factory::M('category');
      $result = $model->field('cat_id')->where("cat_name='{$name}' and parent_id='{$p_id}'")->select();
      return $result;
   }

   public function showError(){

      if(!empty($this->error)){
         $str = '';
         foreach ($this->error as $v) {
            $str .= $v.'<br>';
         }
         return $str;
      }
   }
}