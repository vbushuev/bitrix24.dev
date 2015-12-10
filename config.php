<?php
// source codes path
define("BS_CORE_PATH","../src/");
// connection using proxy
define("BS_USE_PROXY",true);
define("BS_PROXYTYPE", CURLPROXY_HTTP);
define("BS_PROXY", "192.168.11.7");
define("BS_PROXYPORT", 8080);
define("BS_PROXYAUTH", CURLAUTH_NTLM);
define("BS_PROXYUSERPWD", "v.bushuev:Vampire04");
// Bitrix24 data
define("BS_BTRX24_DOMAIN","oookbrenessans.bitrix24.ru");
define("BS_BTRX24_CLIENT_ID","local.565ec55fb6b3c3.75244299");
define("BS_BTRX24_SECRET","235f154afe7a8666ae10221e2a7a7d73");
define("BS_BTRX24_TITLE","FormUpload");
define("BS_BTRX24_REDIRECT_URI","http://bitrix24.bs/test");
//define("BS_BTRX24_code","7uiokgepj220116exosxtpwzi5nes7mk");
//define("BS_BTRX24_member_id","d428a6b5c94fac2b9b84c3c53e984083");

//use autoloader
require(BS_CORE_PATH."core.php");

?>
