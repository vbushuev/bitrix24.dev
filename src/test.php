<?php
define("USE_PROXY",true);
class TestBitrix24{
  protected $options=[
    "host"=>"https://oookbrenessans.bitrix24.ru",
    "timeout"=>"24",
    "connectiontimeout"=>"24",
    "trace"=>1
  ];
  protected $response="";
  public function Send($params=[]){
    $s=curl_init();
    $headers = [
        "Content-type: text/xml;charset=\"utf-8\"",
        "Accept: text/json",
        "Cache-Control: no-cache",
        "Pragma: no-cache"
    ];
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
    curl_setopt($s,CURLOPT_VERBOSE, $this->options['trace']);
    $this->response=curl_exec($s);
    if(curl_errno($s)==CURLE_COULDNT_CONNECT){
        throw new Exception('Couldnt connect.');
    }
    $status = curl_getinfo($s,CURLINFO_HTTP_CODE);
    $last_url = parse_url(curl_getinfo($s, CURLINFO_EFFECTIVE_URL));
    print_r($last_url);
    $err = curl_error($s);
    curl_close($s);
  }
  public function ResponseAsString(){
    return $this->response;
  }
};

$bitrix24=[
  "client_id"=>"local.565ec55fb6b3c3.75244299",
  "secret"=>"235f154afe7a8666ae10221e2a7a7d73",
  "title"=>"FormUpload",
  "redirect_uri"=>"http://bitrix24.bs/src/oauth.php"
];
$t=new TestBitrix24();
$t->Send(["uri"=>"https://oookbrenessans.bitrix24.ru/oauth/authorize/?client_id=".$bitrix24["client_id"]."&response_type=code&redirect_uri=".urldecode($bitrix24["redirect_uri"])]);
echo "RESPONSE:".$t->ResponseAsString();
?>
