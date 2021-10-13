<?php
class RestApi {

    function __construct() {

    }

    public function _init() {
        $this->_setHeader();

        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
            return false;

        if(isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json')
            $_POST = json_decode(file_get_contents('php://input'), true);
        
        $_AapiData = $this->_readData();
        if(gettype($_AapiData) === "string")
            return $_AapiData;

        $_Amatch = $this->_requestCheck($this->_Smethod, $_AapiData);
        $_Sresponse = 'failure_response';
        if(empty($_Amatch)) {
            $_Sresponse = 'response';
            # Auth check
            $_SauthStatus = $this->_authCheck($this->_Surl, $_AapiData);
            if($_SauthStatus !== true)
                $_Sresponse = $_SauthStatus;
        }
        return $this->_sendData($_AapiData[$_Sresponse]);
    }

    private function _setHeader() {
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json");
        header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
        header("Access-Control-Max-Age: 0");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");
    }

    private function _readData() {
        $_Smethod = strtolower($_SERVER['REQUEST_METHOD']);
        $_Sfile = file_get_contents("./DB.json");
        $_Adata = json_decode($_Sfile,1);

        if(!isset($_SERVER['PATH_INFO']))
            return $_Sfile;

        $_Surl = strtolower(trim($_SERVER['PATH_INFO'],'/'));

        if(!isset($_Adata[$_Surl]))
            return $this->_sendData("URL not found");

        if(!isset($_Adata[$_Surl][$_Smethod]))
            return $this->_sendData(strtoupper($_Smethod)." Method not found");

        $this->_Surl = $_Surl;
        $this->_Smethod = $_Smethod;
        return $_Adata[$_Surl][$_Smethod];
    }

    private function _requestCheck($_Smethod, $_AapiData) {
        $_Arequest = $this->_getData($_Smethod);
        return array_diff($_AapiData['request'],$_Arequest);
    }

    private function _authCheck($_Surl, $_AapiData) {
        session_start();
        if($_Surl == "checksession")
            return isset($_SESSION['email_id']) ? 'response' : 'failure_response';
        if($_Surl == "web_app_login")
            $_SESSION = $_AapiData['request'];
        if($_Surl == "web_app_logout")
            session_destroy();
        return true;
    }

    private function _getData($_Smethod="options") {
        $_Adata = array();
        switch ($_Smethod) {
            case "post":
                $_Adata = $_POST;
                break;
            case "get":
                $_Adata = $_GET;
                break;
            case "delete":
            case "put":
            default:
                parse_str(file_get_contents("php://input"),$_Adata);
                break;
        }
        return $_Adata;
    }
    
    private function _sendData($_Sdata) {
        if(gettype($_Sdata) == 'string') {
            return json_encode(array("responseCode"=>1,"response"=>array("Message"=>$_Sdata)));
        }
        if(!in_array($_Sdata['responseCode'], array(0,1)))
            http_response_code($_Sdata['responseCode']);
    
        return json_encode($_Sdata);
    }
}