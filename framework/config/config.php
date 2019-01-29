<?php
/**
 * 框架配置文件
 */

return array(
    //数据库配置信息
    'DB_HOST' => '127.0.0.1',
	'DB_USER' => 'root',
	'DB_PWD' => 'root',
	'DB_NAME' => 'think',
	'DB_CHARSET' => 'utf8',
    'DB_PREFIX' => 'think_',
    
    //smarty配置信息
    'left_delimiter' => '<{',
    'right_delimiter' => '}>',

    //默认模块
    'default_module' => 'home',
    //默认控制器
    'default_controller' => 'Index',
    //默认方法
    'default_medth' => 'IndexAction',
);

