<?php
/* Smarty version 3.1.30, created on 2018-12-24 10:23:32
  from "D:\WWW\self\php\MVC\application\admin\view\category\add.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5c204324a48274_16097824',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0901a423b9703b0c6416dc2f95c3075720e5c510' => 
    array (
      0 => 'D:\\WWW\\self\\php\\MVC\\application\\admin\\view\\category\\add.html',
      1 => 1545618209,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c204324a48274_16097824 (Smarty_Internal_Template $_smarty_tpl) {
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
    <h1>添加分类</h1>
    <form method="POST" action="?m=admin&c=Category&a=addHandleAction" enctype="multipart/form-data">
        分类名称：<input type="text" name="cat_name" id="cat_name"><br/> 
        分类描述：<input type="text" name="cat_desc" id="cat_desc"><br/>
        分类图标：<input type="file" name="cat_logo" id="cat_logo"><br/>
        父级分类：<select name="parent" id="parent">
                    <option value="0" selected/>顶级分类</option><br/>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['datas']->value, 'data');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['cat_id'];?>
" /><?php echo preg_replace('!^!m',str_repeat('--|',$_smarty_tpl->tpl_vars['data']->value['level']),$_smarty_tpl->tpl_vars['data']->value['cat_name']);?>
</option> <br/>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                </select><br/><br/>
        <input type="submit" value="提交">
    </form>
</body>
</html><?php }
}
