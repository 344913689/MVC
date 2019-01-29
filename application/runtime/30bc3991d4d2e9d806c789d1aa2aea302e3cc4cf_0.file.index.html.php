<?php
/* Smarty version 3.1.30, created on 2019-01-04 11:06:32
  from "D:\WWW\self\php\MVC\application\admin\view\Topic\index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5c2ecdb8834d41_45194489',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '30bc3991d4d2e9d806c789d1aa2aea302e3cc4cf' => 
    array (
      0 => 'D:\\WWW\\self\\php\\MVC\\application\\admin\\view\\Topic\\index.html',
      1 => 1546570799,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c2ecdb8834d41_45194489 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html style="font-size:165px">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>话题列表</title>
    <style type="text/css">
        .clearfloat:after{display:block;clear:both;content:"";visibility:hidden;height:0}
        .clearfloat{zoom:1}
    </style>
    <?php echo '<script'; ?>
>
        window.DOMContentLoaded = function() {
            function fixRem() {
                var windowWidth = document.documentElement.clientWidth || window.innerWidth || document.body.clientWidth
                // windowWidth = windowWidth > 750 ? 750 : windowWidth
                var rootSize = 100 * (windowWidth / 375)
                var htmlNode = document.getElementsByTagName("html")[0]
                htmlNode.style.fontSize = rootSize + 'px'
            }
            fixRem()
            window.addEventListener('resize', fixRem, false)
        }
    <?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo PUBLIC_PATH;?>
static/js/jquery-1.11.1.min.js"><?php echo '</script'; ?>
>
</head>
<body>
    <h1>话题列表</h1>
    <input type="submit" value="添加" id="tijiao" onclick="javascript:add()" />
    <div class="clearfloat">
        <?php if (empty($_smarty_tpl->tpl_vars['datas']->value['message'])) {?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['datas']->value, 'data');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
?>
            <div style="float:left; margin: 1rem;">
                <img src="<?php echo $_smarty_tpl->tpl_vars['data']->value['cat_logo'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['data']->value['cat_name'];?>
"/>
                <?php echo $_smarty_tpl->tpl_vars['data']->value['cat_name'];?>
<br/>
                <P><?php echo $_smarty_tpl->tpl_vars['data']->value['cat_desc'];?>
</P> 
                <input type="submit" value="修改" id="xiugai" onclick="javascript:edit(<?php echo $_smarty_tpl->tpl_vars['data']->value['cat_id'];?>
)" />
                <input type="submit" value="删除" id="shanchu" onclick="javascript:del(<?php echo $_smarty_tpl->tpl_vars['data']->value['cat_id'];?>
)" />
            </div>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        <?php } else { ?>
            <?php echo $_smarty_tpl->tpl_vars['datas']->value['message'];?>
<br/>
        <?php }?>
    </div>
    <hr><br/>

</body>
<?php echo '<script'; ?>
 type="text/javascript">
    add = function () {
        window.location.href = "?m=admin&c=category&a=addAction"
    }
    edit = function (id) {
        window.location.href ="?m=admin&c=category&a=editAction&id="+id
        //$.get(url = "?m=admin&c=category&a=editAction&id="+id)
    }
    del = function (id) {
        if(confirm("删除后无法恢复，你确认要删除！")){
            window.location.href ="?m=admin&c=category&a=deleteAction&id="+id
        } else {
            return false;
        }
    }
<?php echo '</script'; ?>
>
</html>
<?php }
}
