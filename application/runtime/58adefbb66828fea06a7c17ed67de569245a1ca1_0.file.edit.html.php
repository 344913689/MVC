<?php
/* Smarty version 3.1.30, created on 2018-12-24 10:16:14
  from "D:\WWW\self\php\MVC\application\admin\view\category\edit.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5c20416ee2c1a8_28086265',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '58adefbb66828fea06a7c17ed67de569245a1ca1' => 
    array (
      0 => 'D:\\WWW\\self\\php\\MVC\\application\\admin\\view\\category\\edit.html',
      1 => 1545613681,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c20416ee2c1a8_28086265 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>分类表单——添加</title>
</head>
<body>
    <h1>编辑分类</h1>
    <form method="POST" action="?m=admin&c=Category&a=updateAction" enctype="multipart/form-data">        
        分类名称：<input type="text" name="cat_name" id="cat_name" value="<?php echo $_smarty_tpl->tpl_vars['cat_list']->value['cat_name'];?>
"><br/> 
        分类描述：<input type="text" name="cat_desc" id="cat_desc" value="<?php echo $_smarty_tpl->tpl_vars['cat_list']->value['cat_desc'];?>
"><br/>
        <?php if (isset($_smarty_tpl->tpl_vars['cat_list']->value['cat_logo'])) {?>
        <img alt="" src="<?php echo $_smarty_tpl->tpl_vars['cat_list']->value['cat_logo'];?>
" style="max-width:50px; max-height:50px;">
        <font color="red">图标已存在，再次上传将覆盖！</font><br/>
        分类图标：<input type="file" name="cat_logo" id="cat_logo"><br/>
        <?php } else { ?>
        分类图标：<input type="file" name="cat_logo" id="cat_logo"><br/>
        <?php }?>
        父级分类：<select name="parent_id" id="parent_id">
                {switch name="<?php echo $_smarty_tpl->tpl_vars['parent_list']->value['cat_id'];?>
" }
                    {case value="<?php echo $_smarty_tpl->tpl_vars['parent_list']->value['cat_id'];?>
" break="0"}<option value="<?php echo $_smarty_tpl->tpl_vars['parent_list']->value['cat_id'];?>
" /><?php echo $_smarty_tpl->tpl_vars['parent_list']->value['cat_name'];?>
</option><br/>{/case}
                {/switch}
                </select><br/>
        <input type="hidden" name="cat_id" value="<?php echo $_smarty_tpl->tpl_vars['cat_list']->value['cat_id'];?>
">
        <input type="hidden" name="old_cat_logo" value="<?php echo $_smarty_tpl->tpl_vars['cat_list']->value['cat_logo'];?>
" >
        <input type="submit" value="提交">
    </form>
</body>
</html><?php }
}
