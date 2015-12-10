<?php
namespace Http{
  class Cookie {
    protected $data=[];
    public function __set($n,$v){
      $this->data[$n]=$v;
    }
    public function __get($n){
      return isset($this->data[$n])?$this->data[$n]:null;
    }
    public function asHeaderString(){
      $res="";
      foreach($this->data as $n=>$v)$res.="{$n}={$v};";
      return $res;
    }
  }
}
?>
