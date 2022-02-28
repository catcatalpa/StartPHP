<?php
//此文件为模版引擎配置文件

//设置字符编码UTF-8
header('Content-Type:text/html;charset=utf-8');

$templatesConfig = array(
    //模版文件名后缀
    'suffix'=> '.php',
      
    //编译后的模板存放文件夹
    'tplc' => CACHE.'tplc/',
    
    //是否编译成静态html文件（即不编译自定义字段=>{{ xxx }}）
    'isstatic' => false,
    
    //是否兼容原生PHP语法
    'phpc' => true,
    
    //是否开启缓冲区
    'iscache' => true
 );