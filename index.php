<?php
ini_set("display_errors",1);
$_Smethod = strtolower($_SERVER['REQUEST_METHOD']);
$_Sfile = file_get_contents("./DB.json");
$_Adata = json_decode($_Sfile,1);

if(!isset($_SERVER['PATH_INFO']))
    exit("<pre>".$_Sfile."</pre>");

$_Surl = strtolower(trim($_SERVER['PATH_INFO'],'/'));

function _sendData($_Sdata) {
    if(gettype($_Sdata) == 'string') {
        return json_encode(array("responseCode"=>1,"response"=>array("Message"=>$_Sdata)));
    }
    if(!in_array($_Sdata['responseCode'], array(0,1)))
        http_response_code($_Sdata['responseCode']);

    return json_encode($_Sdata);
}

function _getData($_Smethod) {
    $_Adata = array();
    switch ($_Smethod) {
        case "post":
            $_Adata = $_POST;
            break;
        case "get":
            $_Adata = $_GET;
            break;
        case "delete":
            parse_str(file_get_contents("php://input"),$_Adata);
            break;
        case "put":
            parse_str(file_get_contents("php://input"),$_Adata);
            break;
        default:
            $_Adata = array();
            break;
    }
    return $_Adata;
}

if(!isset($_Adata[$_Surl]))
    exit(_sendData("URL not found"));

if(!isset($_Adata[$_Surl][$_Smethod]))
    exit(_sendData(strtoupper($_Smethod)." Method not found"));

$_AapiData = $_Adata[$_Surl][$_Smethod];

session_start();

$_Arequest = _getData($_Smethod);
$_Amatch = array_diff($_AapiData['request'],$_Arequest);

if($_Surl == "checksession")
    exit(_sendData(isset($_SESSION['email_id']) ? $_AapiData['response'] : $_AapiData['failure_response']));

if(empty($_Amatch)) {
    if($_Smethod == "web_app_login")
        $_SESSION = $_AapiData['request'];
    if($_Smethod == "web_app_logout")
        session_destroy();
    exit(_sendData($_AapiData['response']));
}
exit(_sendData($_AapiData['failure_response']));