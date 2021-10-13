<?php
ini_set("display_errors",1);

require_once("./class.restApi.php");
$_OrestApi = new RestApi();

if(isset($_GET['form'])) {
    $_Adata = json_decode(file_get_contents("php://input"),1);
    $_OrestApi->__construct($_Adata['url'], $_Adata['method']);
    echo $_OrestApi->_writeData($_Adata['request'], $_Adata['response'], $_Adata['failureResponse']);
} else {
    echo $_OrestApi->_init();
}