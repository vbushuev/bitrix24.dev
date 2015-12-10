<?php
namespace Core{
  class Logger{
    public static function info($s){
      if(is_object($s)){
        ob_start();
        preint_r($s);
        $s=ob_get_clean();
      }
      $s=date("Y-m-d H:i:s\t").$s."\n";
      file_put_contents(BS_CORE_PATH."../logs/bitrix24-".date("Y-m-d").".log",$s,FILE_APPEND);
    }
  }
}
?>
