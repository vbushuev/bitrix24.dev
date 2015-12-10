<?php
$t=new Bitrix24\Bitrix24();

if(isset($_GET["code"]))
$bitrix24=[
  "client_id"=>"local.565ec55fb6b3c3.75244299",
  "secret"=>"235f154afe7a8666ae10221e2a7a7d73",
  "title"=>"FormUpload",
  "redirect_uri"=>"http://bitrix24.bs/src/oauth.php"
  //"redirect_uri"=>"https://apps-b1594903.bitrix24-cdn.com/b1594903/app_local/89b8a6f083261d3350638a57cbc010a6/src/oauth.php"
];
$t->Send(["uri"=>"https://oookbrenessans.bitrix24.ru/oauth/authorize/?client_id=".$bitrix24["client_id"]."&response_type=code&redirect_uri=".urldecode($bitrix24["redirect_uri"])]);
//echo "RESPONSE:".$t->ResponseAsString();
?>
