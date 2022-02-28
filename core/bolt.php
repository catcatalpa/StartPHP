<?php
class bolt{
    public function init(){
        //Autoload
        require_once CONFIG.'config'.'.php';
        $this->boltLoader();
        spl_autoload_register(function ($className) {
        //解析文件名，得到文件的存放路径
        list($filename , $suffix) = explode('_' , $className);
     
        //构成文件路径
        $file = DIR . '/model/' . strtolower($filename) . '.php';
        
        //获取文件
        if (file_exists($file))
        {
            //引入文件
            require_once($file);        
        }
        else if ($className == "route") {
            require_once CONTROLLER.'route'.'.php';
        }
        else
        {
            echo($file."<br>");
            //文件不存在
            die("File '$file' containing class '$className' not found.");
        }
    });
    $route = new route();
    $run = $route -> run();
    }
    public function boltLoader(){
        if (FRAME_WORK_NAME != "StartPHP") {
            die('Error: An unauthorized commercial version of the framework was used(ECode100001).');
        }
    }
}
?>