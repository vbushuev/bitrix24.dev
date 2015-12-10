<?php
namespace Bitrix24{
  class Bitrix24{
    protected $options=[
      "timeout"=>"24",
      "connectiontimeout"=>"24",
      "trace"=>1
    ];
    protected $data=[
      "domain"=>BS_BTRX24_DOMAIN,
      "client_id"=>BS_BTRX24_CLIENT_ID,
      "secret"=>BS_BTRX24_SECRET,
      "title"=>BS_BTRX24_TITLE,
      "redirect_uri"=>BS_BTRX24_REDIRECT_URI
    ];
    protected $response="";
    protected $cookie;
    protected function initCurl($url){
      $s=curl_init();
      $headers = [
          "Content-type: text/xml;charset=\"utf-8\"",
          "Accept: text/json",
          "Cache-Control: no-cache",
          "Pragma: no-cache"
      ];
      $headers[]="Cookie: ".$this->cookie->asHeaderString();
      curl_setopt($s,CURLOPT_URL,$url);
      curl_setopt($s,CURLOPT_TIMEOUT,$this->options['timeout']);
      curl_setopt($s,CURLOPT_CONNECTTIMEOUT,$this->options['connectiontimeout']);
      curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($s,CURLOPT_HTTPHEADER,$headers);
      if(BS_USE_PROXY){
        curl_setopt($s,CURLOPT_PROXYTYPE, BS_PROXYTYPE);
        curl_setopt($s,CURLOPT_PROXY, BS_PROXY);
        curl_setopt($s,CURLOPT_PROXYPORT, BS_PROXYPORT);
        curl_setopt($s,CURLOPT_PROXYAUTH, BS_PROXYAUTH);
        curl_setopt($s,CURLOPT_PROXYUSERPWD, BS_PROXYUSERPWD);
      }
      curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);
      if($this->options['trace']){
        $fp=fopen(BS_CORE_PATH.'../logs/curl-'.date("Y-m-d").'.log', 'wa');
        curl_setopt($s,CURLOPT_VERBOSE, $this->options['trace']);
        curl_setopt($s, CURLOPT_STDERR, $fp);
      }
      return $s;
    }
    protected function oauthGetcode(){
      $url="https://".$this->data["domain"]."/oauth/authorize/?client_id="
          .$this->data["client_id"]
          ."&response_type=code"
          ."&redirect_uri=".urldecode($this->data["redirect_uri"]);
      header("Location: {$url}");
    }
    protected function getToken(){
      if(isset($_GET["code"])){
        $this->data["code"]=$_GET["code"];
        $url="https://".$this->data["domain"]."/oauth/token/?"
            ."client_id=".$this->data["client_id"]
            ."&grant_type=authorization_code"
            ."&code=".$this->data["code"]
            ."&client_secret=".$this->data["secret"]
            ."&redirect_uri=".urldecode($this->data["redirect_uri"])
            ."&scope=user";
        $this->Send(["url"=>$url]);
        $js=$this->ResponseAsJson();
        //print_r($js);
        if(!isset($js->access_token))throw new Exception("Bitrix24 - no connection");
        $this->data["access_token"]=$js->access_token;
        $this->data["expires_in"]=$js->expires_in;
        $this->data["scope"]=$js->scope;
        $this->data["user_id"]=$js->user_id;
        $this->data["status"]=$js->status;
        $this->data["member_id"]=$js->member_id;
        $this->data["refresh_token"]=$js->refresh_token;
        $this->data["domain"]=$js->domain;
        return true;
      }
      return false;
    }
    protected function 
    public function __construct(){
      $this->cookie=new \Http\Cookie;
      $this->cookie->BITRIX_SM_CC="Y";
      $this->cookie->BITRIX_SM_PK="Y";
      $this->cookie->BITRIX_SM_SOUND_LOGIN_PLAYED="Y";
      $this->cookie->BITRIX_SM_TIME_ZONE="-180";
      //$this->cookie->BX_USER_ID="";
      if(!$this->getToken())$this->oauthGetcode();
    }
    public function Send($params=[]){
      $s=$this->initCurl($params["url"]);
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
