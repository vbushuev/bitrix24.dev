<?php
file_put_contents(dirname(__FILE__)."/db/log.log", date('c').": ".var_export($_REQUEST, 1)."\n", FILE_APPEND);

if(is_array($_REQUEST["auth"]) && isset($_REQUEST["auth"]["access_token"]))
{
   $portal = $_REQUEST["auth"]["domain"];

   $requestValue = $_REQUEST["properties"]["inputString"];
   $responseValue = md5($requestValue);

   $c = curl_init("http://" . $portal . "/rest/bizproc.event.send.json");

   $params = array(
      "auth" => $_REQUEST["auth"]["access_token"],
      "event_token" => $_REQUEST["event_token"],
      "log_message" => "Got '".$requestValue."' string!",
      "return_values" => array(
         "outputString" => $responseValue,
      )
   );

   curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($c, CURLOPT_HEADER, true);
   curl_setopt($c, CURLINFO_HEADER_OUT, true);
   curl_setopt($c, CURLOPT_VERBOSE, true);
   curl_setopt($c, CURLOPT_POST, true);
   curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params));

   $response = curl_exec($c);

   file_put_contents(dirname(__FILE__)."/db/log.log", "response: ".$response."\n", FILE_APPEND);
}
?>