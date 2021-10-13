<?php
ini_set("display_errors",1);

require_once("./class.restApi.php");
$_OrestApi = new RestApi();
echo $_OrestApi->_init();