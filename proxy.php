<?php
// ************************************************************************
// name: RPC•X PROXY
// author: Dapper x.com/SolDapper
// license: MIT
// ************************************************************************

// settings ***************************************************************
// $whitelist = false; will allow all origins
$whitelist = array("https://yourwebsite.com","https://anotherwebsite.com"); 
$key = "helius-key-here";
$path = "https://mainnet.helius-rpc.com/?api-key=".$key;
// redirect failed connections
$fail = "https://www.google.com/search?rlz=1C1ONGR_enUS1036US1036&sxsrf=AB5stBgOtYYWYM0lRLqCwqiLbb_JWud7iw:1689913872575&q=loser&tbm=isch&sa=X&ved=2ahUKEwj0is3g-56AAxVIQzABHSLCCK4Q0pQJegQIBBAB&biw=2158&bih=1192&dpr=1.1";
// settings ***************************************************************

// headers ****************************************************************
if($whitelist!=false){
  if (isset($_SERVER['HTTP_ORIGIN'])) {
    if(!in_array($_SERVER['HTTP_ORIGIN'],$whitelist)){
      header("Location: ".$fail);
      exit;
    }
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  }
  else{
    header("Location: ".$fail);
    exit;
  }
}
else{
  header('Access-Control-Allow-Origin:*');
}
header('Access-Control-Allow-Credentials:true');
header('Access-Control-Max-Age:86400');
if($_SERVER['REQUEST_METHOD']=='OPTIONS'){
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
  header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");         
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
  header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}
header('Content-Type: application/json');
// headers **************************************************************

// curl class ***********************************************************
$json=file_get_contents('php://input');
$dat=json_decode($json);
if (!class_exists('RPCX')) {
 class RPCX {
  public function call($path,$method,$params){
    $payload=new stdClass;
    $payload->jsonrpc="2.0";
    $payload->id="rpd-op-69";
    $payload->method=$method;
    $payload->params=$params;
    $data=json_encode($payload);
    $ch=curl_init($path);
    $options=array( 
      CURLOPT_URL=>$path, 
      CURLOPT_RETURNTRANSFER=>true,
      CURLOPT_HTTPHEADER=>array('Content-Type:application/json'),
      CURLOPT_POSTFIELDS=>$data
    );
    curl_setopt_array($ch,$options);
    $result=json_decode(curl_exec($ch));
    if (curl_errno($ch)) {
      $error=new stdClass;
      $error->status="curl error";
      $error->message=curl_error($ch);
      $error->version=curl_version();
      $error->CURL_VERSION_ASYNCHDNS=CURL_VERSION_ASYNCHDNS;
      $result=$error;
    }
    curl_close( $ch );
    return $result;
  }
 }
 $rpcx=new RPCX();
}
// curl class *********************************************************

// output *************************************************************
if(isset($dat->method) && isset($dat->params)){
  echo json_encode($rpcx->call($path,$dat->method,$dat->params));
}
// output ************************************************
