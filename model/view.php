<?php
class View_Model
{
    private $data = array();
    private $render = false;

    public function __construct($template)
    {
        $templates = explode("?",$template);
        //var_dump($templates);
        $template = $templates[count($templates)-1];
        global $url;
        //echo($templates[0]);
        if($templates[0] != "*" && substr($templates[0],0,1) != "/"){
        $url = $templates[0]."/";
        }
        else if($templates[0] != "*" && substr($templates[0],0,1) == "/"){
        $url = $templates[0];
        }
        else {
            $url = "default/";
        }
        //构成完整文件路径
            $file = APP.$url.strtolower($template).".php";
            $this->tpl = APP.$url.strtolower($template).".php";
            //echo($tpl);
        if (file_exists($file))
        {
            $this->render = $file;
            $this->url = $url;
        }
        else {
            die('Error:page is not found!');
        }
    }
 
    /**
     * 接受从控制器赋予的变量，并保存在data数组中
     * 
     * @param $variable
     * @param $value
     */
    public function assign($variable , $value,$page)
    {
        $this->data[$variable] = $value;
        
    }
 
    public function __destruct()
    {
        //把类中的data数组变为该函数的局部变量，以方便在视图模板中使用
        $data = $this->data;
        //渲染视图
        $filename = $this->parseFile($this->render);
        //echo("111");
        $fp = fopen($this->render,"r");
        new Varsparse_Model(fread($fp,filesize($this->render)));
        $tpl = new Template_Model;
        //echo($this->tpl);
        $tpl->display($this->tpl);
        //$this->replace($this->render);
        
    }
    public function parseFile($filePath)
    {
        $orginname = str_replace(APP.$this->url,'',$filePath);
        return($filename = str_replace('.php','',$orginname));
    }
}