<?php
  
/**
 * 模版解析类
 */
class Varsparse_Model
{
  // 字段，接收模版文件内容

  public function __construct($_tpl)
    {
        var_dump($_tpl);
        $this->_tpl = $_tpl;
    }
  // 解析普通变量
  private function parvar()
  {
    //var_dump($this->_tpl);
    $_patten = '/[^{{\\}}]/';
    if (preg_match($_patten,$this->_tpl)) {
      $this->_tpl = preg_replace($_patten, "<?php echo \$this->_vars['$1'];?>",$this->_tpl);
      //echo($this->_tpl);
    }
  }
  
  //解析IF语句
  private function parif(){
    $_pattenif = '/{{\s+\{if\s+\$([\w]+)\}\s+}}/';
    $_pattenElse = '/{{\s+\{else\}\s+}}/';
    $_pattenEndif = '/{{\s+\{\/if\}\s+}}/';
    if (preg_match($_pattenif,$this->_tpl)) {
      if (preg_match($_pattenEndif,$this->_tpl)) {
        $this->_tpl = preg_replace($_pattenif,"<?php if (\$this->_vars['$1']){?>",$this->_tpl);
        $this->_tpl = preg_replace($_pattenEndif,"<?php } ?>",$this->_tpl);
        if (preg_match($_pattenElse,$this->_tpl)) {
          $this->_tpl = preg_replace($_pattenElse,"<?php }else{?>",$this->_tpl);
        }
      }else{
      echo 'Error: "if" statement syntax error(ECode100003)';
      }
    }
  }
  
  //PHP注释解析
  
  private function parCommon(){
    $_pattenCommon = '/{{\s+\{#\}(.*)\{#\}\s+}}/';
    if (preg_match($_pattenCommon,$this->_tpl)) {
      $this->_tpl = preg_replace($_pattenCommon,"<?php /* $1 */ ?>",$this->_tpl);
    }
  }
    
  //解析foreach语句
  private function parForeach(){
    $_pattenForeach = '/{{\s+\{foreach\s+\$([\w]+)\(([\w]+),([\w]+)\)\}\s+}}/';
    $_pattenForeachEnd = '/{{\s+\{\/foreach\}\s+}}/';
    $_pattenForeachValue = '/{{\s+\{@([\w]+)\}\s+}}/';
    if (preg_match($_pattenForeach,$this->_tpl)) {
      if (preg_match($_pattenForeachEnd,$this->_tpl)) {
        $this->_tpl = preg_replace($_pattenForeach, "<?php foreach (\$this->_vars['$1'] as \$$2=>\$$3) {?>", $this->_tpl);
        $this->_tpl = preg_replace($_pattenForeachEnd, "<?php }?>", $this->_tpl);
        if (preg_match($_pattenForeachValue, $this->_tpl)) {
          $this->_tpl = preg_replace($_pattenForeachValue,"<?php echo \$$1;?>",$this->_tpl);
        }
      }else{
      echo 'Error: "foreach" statement syntax error(ECode100004)';  
      }
    }
  }
  
  //解析include方法
  private function parInclude(){
    $_pattenInclude = '/{{\s+\{include\s+file=\"([\w\.\-]+)\"\}\s+}}/';
    if (preg_match($_pattenInclude,$this->_tpl,$_file,$_file)) {
      if (!file_exists($_file[1])||empty($_file)) {
        echo 'Error: The status of the included file is abnormal(ECode100004)';
      }
      $this->_tpl = preg_replace($_pattenInclude,"<?php include '$1';?>",$this->_tpl);
    }
  }
  
  //解析系统变量方法
  private function parConfig(){
    $_pattenConfig = '/{{\s+\{([\w]+)\}\s+}}/';
    if (preg_match($_pattenConfig,$this->_tpl)) {
      $this->_tpl = preg_replace($_pattenConfig,"<?php echo \$this->_config['$1'];?>",$this->_tpl);
    }
  }
  // 对外公共方法
  public function compile($_path)
  {
    // 解析模版文件
    $this->parvar();
    $this->parif();
    $this->parForeach();
    $this->parInclude();
    $this->parCommon();
    $this->parConfig();
    // 校验编译文件
    if (!file_put_contents($_path, $this->_tpl)) {
        //var_dump($_path);
      exit('Error: Some unexpected errors occurred while compiling the file generation(ECode100005)');
    }
  }
}