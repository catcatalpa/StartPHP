<?php
  
/**
 * 模版类
 */
class Template_Model
{
  //注入变量
  private $_vars = array();
  //保存系统变量数组字段
  private $_config = array();
  //assign()方法，用于注入变量
  public function assign($_var,$_value){
    //$_var用于同步模版里的变量名
    //$_value表示值
    if (isset($_var)&&!empty($_var)) {
      $this->_vars[$_var] = $_value;
    }else{
      exit('ERROR:The variable is not set(ECode100006)');
    }
  }
  
  //display()方法
  public function display($template)
  {
      //var_dump($template);
    $_tplFile =  $template;
    $_file = substr($_tplFile,strlen("/")+strpos($_tplFile,"/"),(strlen($_tplFile)-strpos($_tplFile,".php"))*(-1));
    echo($_file);
    // 判断文件是否存在
    if (! file_exists($_tplFile)) {
      exit('Error: File path error or no such directory(ECode100002)');
    }
  
    //生成编译文件
    include(CONFIG.'template.php');
    $this->_path = $templatesConfig['tplc'].md5($_file).'-'.$_file.'.php';
    //缓存文件
    $_cacheFile = CACHE.md5($_file).'-'.$_file.'.html';
    //当第二次运行相同文件，直接载入缓存文件
    if ($templatesConfig['iscache']) {
        //echo("111");
      //判断缓存文件和编译文件都存在
      if (file_exists($_cacheFile)&&file_exists($_path)) {
        //判断模版文件是否修改过
        if (filemtime($this->_path)>=filemtime($_tplFile)&&filemtime($_cacheFile)>=filemtime($this->_path)) {
          include $_cacheFile;
          echo '<!--cache-->';
          return;
        }
      }
    }
    //当编译文件不存在或者文件发生改变则重新生成
    if (!file_exists($this->_path)||filemtime($this->_path)<filemtime($_tplFile)) {
      //echo("111");
      require_once MODEL.'varsparse.php';
      //构造方法是传入模版文件地址
      $_parser = new Varsparse_Model($_tplFile);
      //传入编译文件地址
      $_parser->compile($this->_path);
    }
    require_once MODEL.'varsparse.php';
    $_parser = new Varsparse_Model($_tplFile);
    $_parser->compile($this->_path);
    //载入编译文件
    include $this->_path;
    //echo($this->_path);
  }
}
?>