<?php
//系统基础配置
define('FRAME_WORK_NAME','StartPHP');//框架名称，用于核实版权，非商业授权请勿擅自改动
define('APP',DIR.'/app/');//app目录
define('ADMIN',APP.'/admin/');//admin目录
define('CACHE',DIR.'/cache/');//app目录
define('CONTROLLER',DIR.'/controller/');//controller目录
define('LIB',DIR.'/lib/');//lib目录
define('MODEL',DIR.'/model/');//model目录
define('STATIC',DIR.'/static/');//static目录
define('VENDOR',DIR.'/vendor/');//vendor目录
define('VIEW',DIR.'/view/');//view目录
define('INSTALL',APP.'/install/');//install目录
define('APP_NAME','StartPHP');//项目名称
define('PROTOCOL_TYPE','http://');//站点http协议类型（选填http://或https://）
define('SITE_DOMAIN','101.43.57.133');//站点主域名，不需要加http前缀，也不要加斜杠等后缀

//数据库参数配置
define('DATABASE_TYPE','mysql');
define('DATABASE_IP','localhost');
define('DATABASE_NAME','startphp');
define('DATABASE_ROOT','startphp');
define('DATABASE_PASSWORD','');
define('DATABASE_PREFIX','StartPHP_');
define('TEMPLATENAME','default');//模板名称
define('TEMPLATE',APP.TEMPLATENAME.'/');

//要引入的自定义配置文件可以写在这里
include_once(CONFIG.'template.php');