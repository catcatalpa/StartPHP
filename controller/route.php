<?php
class route{
    function run(){
        //获取请求参数
        $request = $_SERVER['QUERY_STRING'];
        //获取请求文件
        $url = $_SERVER['PHP_SELF'];
        $url = str_replace('.php','',$url);
        if (substr($url, -1) == "/") {
            $url = rtrim($url, "/"); 
        }
        $url = str_replace('/index/','',$url);
        if (substr($url,0,1) == "/") {
            $file= substr( $url , strrpos($url , '/')+1 );
        }
        else{
            $file = $url;
        }
        //解析其它GET变量
        //controller是第一个元素
        $parsed = explode("&" , parse_url($_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"])['query']);
        $param = array();
        for ($i=0; $i<count($parsed); $i++)
        {
            $data = explode("=",$parsed[$i]);
            $param[$data[0]] = $data[1];
        }
        $controller = $param['ctrler'];
        if ($page == "") {
            $page = $file;
        }
        //echo($controller);
        //剩余的为GET变量，也把它们解析出来
        $getVars = array();
        foreach ($parsed as $argument)
        {
            //split GET vars along '=' symbol to separate variable, values
            list($variable , $value) = explode('=' , $argument);
            $getVars[$variable] = $value;
        }
        if ($file == "" && $page == "") {
            $page = "index";
            $file = "index";
            $page=str_replace('.php','',$file);
        }
        if (file_exists(TEMPLATE.$file) && $file == "index.php" && $page == "") {
            $page = "index";
            $page=str_replace('.php','',$file);
        }
        if (!file_exists(CACHE."installed.lock") && file_exists(INSTALL."index.php")) {
            //echo("111");
            $controller = "install";
            $page = "install";
        }
        //echo($page);
        // if (file_exists(TEMPLATE.$file)) {
        //     var_dump(array_shift($parsed));
        // }
        //构成控制器文件路径
        $target = DIR . '/controller/' . $controller . '.php';
        //echo($target);
        //引入目标文件
        if (file_exists($target))
        {
            include_once($target);
         
            //修改page变量，以符合命名规范
            $class = ucfirst($controller) . '_Controller';
         
            //初始化对应的类
            if (class_exists($class))
            {
                $controller = new $class;
            }
            else
            {
                //类的命名正确吗？
                die('class does not exist!');
            }
        }
        else if(file_exists(TEMPLATE.$file.".php")||file_exists(TEMPLATE.$page.".php")){
            include_once(DIR . '/controller/default.php');
            $page = "*?".$file;
            $class = 'Default_Controller';
            //初始化对应的类
            if (class_exists($class))
            {
                $controller = new $class;
                //$page = $file;
            }
            else
            {
                //类的命名正确吗？
                die('class does not exist!');
            }
        }
        else
        {
            //不能在controllers找到此文件
            die('page does not exist!');
        }
         
        //一但初始化了控制器，就调用它的默认函数main();
        //把get变量传给它
        if (!file_exists(CACHE."installed.lock") && file_exists(INSTALL."index.php")) {
            //echo("111");
            $page = "install*index";
        }
        $controller->main($getVars,$page);
    }
}