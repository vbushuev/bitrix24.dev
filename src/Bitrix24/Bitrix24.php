<?php
namespace Bitrix24{
  class Bitrix24{
    protected $options=[
      "host"=>"https://oookbrenessans.bitrix24.ru",
      "timeout"=>"24",
      "connectiontimeout"=>"24",
      "trace"=>1
    ];
    protected $response="";
    protected $cookie;
    public function __construct(){
      $this->cookie=new \Http\Cookie;
      $this->cookie->BITRIX_SM_CC="Y";
      $this->cookie->BITRIX_SM_PK="Y";
      $this->cookie->BITRIX_SM_SOUND_LOGIN_PLAYED="Y";
      $this->cookie->BITRIX_SM_TIME_ZONE="-180";
      //$this->cookie->BX_USER_ID="";
    }
    public function Send($params=[]){
      $s=curl_init();
      $headers = [
          "Content-type: text/xml;charset=\"utf-8\"",
          "Accept: text/json",
          "Cache-Control: no-cache",
          "Pragma: no-cache"
      ];
      $headers[]="Cookie: ".$this->cookie->asHeaderString();
      print("Connecting to ".(isset($params["uri"])?$params["uri"]:$this->options['host'])."\n");
      curl_setopt($s,CURLOPT_URL,(isset($params["uri"])?$params["uri"]:$this->options['host']));
      curl_setopt($s,CURLOPT_TIMEOUT,$this->options['timeout']);
      curl_setopt($s,CURLOPT_CONNECTTIMEOUT,$this->options['connectiontimeout']);
      curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($s,CURLOPT_HTTPHEADER,$headers);
      if(USE_PROXY){
        curl_setopt($s,CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        curl_setopt($s,CURLOPT_PROXY, "192.168.11.7");
        curl_setopt($s,CURLOPT_PROXYPORT, 8080);
        curl_setopt($s,CURLOPT_PROXYAUTH, CURLAUTH_NTLM);
        curl_setopt($s,CURLOPT_PROXYUSERPWD, "v.bushuev:Vampire04");
      }
      //curl_setopt($s,CURLOPT_POST,true);
      //curl_setopt($s,CURLOPT_POSTFIELDS,$params['data']);
      curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);
      if($this->options['trace']){
        $fp=fopen(CORE_PATH.'../logs/curl-'.date("Y-m-d").'.log', 'wa');
        curl_setopt($s,CURLOPT_VERBOSE, $this->options['trace']);
        curl_setopt($s, CURLOPT_STDERR, $fp);
      }

      $this->response=curl_exec($s);
      if(curl_errno($s)==CURLE_COULDNT_CONNECT){
          throw new Exception('Couldnt connect.');
      }
      $status = curl_getinfo($s,CURLINFO_HTTP_CODE);
      $last_url = parse_url(curl_getinfo($s, CURLINFO_EFFECTIVE_URL));
      //print_r($last_url);
      $err = curl_error($s);
      curl_close($s);
    }
    public function ResponseAsString(){
      return $this->response;
    }
    public function ResponseAsJson(){
      return json_decode($this->response);
    }
  }
}
?>
