<?php
class RestApi {
    
    private $_Surl = '';
    private $_Smethod = 'GET';
    private $_SdbFile = "./DB.json";
    private $_JdbData = '';
    private $_Odb;
    private $_isProd = false;

    function __construct($_Surl='', $_Smethod = 'GET') {
        $this->_Surl = $_Surl;
        $this->_Smethod = $_Smethod;
        $this->_isProd = !(in_array($_SERVER['HTTP_HOST'],array('localhost','127.0.0.1','::1')));
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
        if(isset($_SERVER['HTTP_REFERER']))
            header('Access-Control-Allow-Origin: '.trim($_SERVER['HTTP_REFERER'],'/'));
        header("Content-Type: application/json");
        header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
        header("Access-Control-Max-Age: 0");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");
    }

    private function _readData() {
        if(!file_exists($this->_SdbFile)) {
            if($this->_isProd === false)
                copy('./DB_sample.json',$this->_SdbFile) OR DIE('Error in creating JSON file');
            else
                file_put_contents($this->_SdbFile, $this->_reteriveData());
        }
        $_Smethod = strtolower($_SERVER['REQUEST_METHOD']);
        $_Sfile = file_get_contents($this->_SdbFile);
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
        session_set_cookie_params(["SameSite" => "None"]); //none, lax, strict
        session_set_cookie_params(["Secure" => "false"]); //false, true
        // session_set_cookie_params(["HttpOnly" => "true"]); //false, true
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
                $_Adata = json_decode(file_get_contents("php://input"),1);
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

    private function _checkJson($_Jdata = '') {
        $_Adata = (!$_Jdata) ? array() : json_decode($_Jdata,1);
        if ($_Adata === null && json_last_error() !== JSON_ERROR_NONE)
            return false;
        return $_Adata;
    }

    public function _writeData($_Jrequest = '', $_Jresponse = '', $_JfailureResponse = '') {
        if(!$this->_Surl || !$this->_Smethod)
            return $this->_sendData('URL or Method is missing..');
        if(!$_Jrequest && !$_Jresponse && !$_JfailureResponse)
            return $this->_sendData('Request or Response or Failure json should be there..');
        
        $_Arequest = $this->_checkJson($_Jrequest);
        $_Aresponse = $this->_checkJson($_Jresponse);
        $_AfailureResponse = $this->_checkJson($_JfailureResponse);
        
        if($_Arequest === false || $_Aresponse === false || $_AfailureResponse === false)
            return $this->_sendData('Invalid JSON');

        $_AnewData = array(
            "request" => $_Arequest,
            "response" => $_Aresponse,
            "failure_response" => $_AfailureResponse
        );

        $_AapiData = json_decode($this->_readData(),1);
        $this->_Smethod = strtolower($this->_Smethod);
        if(!isset($_AapiData[$this->_Surl]))
            $_AapiData[$this->_Surl] = array($this->_Smethod => $_AnewData);
        else
            $_AapiData[$this->_Surl][$this->_Smethod] = $_AnewData;
        
        @chmod($this->_SdbFile,0777);
        file_put_contents($this->_SdbFile,json_encode($_AapiData));
        if($this->_isProd === true)
            $this->_migrateData();
        return $this->_sendData(array("responseCode"=>0,"response"=>array("Message"=>"Data submitted successfully")));
    }

    private function _connectDB() {
        if(!is_object($this->_Odb)) {
            require_once("class.db.php");
            $this->_Odb = new DB();
        }
        return $this->_Odb;
    }

    private function _reteriveData() {

        if($this->_JdbData != '')
            return $this->_JdbData;
            
        $this->_connectDB();
        
        $rows = $this->_Odb->_select('SELECT * FROM services');
        $_AapiData = array();
        foreach($rows as $row) {
            $_AapiData[$row['url']][$row['method']] = array( 
                'request' => json_decode($row['request'],1),
                'response' => json_decode($row['response'],1),
                'failure_response' => json_decode($row['failure_response'],1)
            );
        }
        return $this->_JdbData = json_encode($_AapiData, JSON_UNESCAPED_SLASHES);
    }

    private function _migrateData() {
        $this->_connectDB();
        
        $_AapiData = json_decode(file_get_contents($this->_SdbFile),1);
        $this->_Odb->_query('TRUNCATE TABLE services');

        foreach($_AapiData as $_Surl => $_Aapi) {
            foreach($_Aapi as $_Smethod => $_Adata) {
                if(!isset($_Adata['request']))
                    $_Adata['request'] = array();
                    if(!isset($_Adata['response']))
                        $_Adata['response'] = array();
                if(!isset($_Adata['failure_response']))
                    $_Adata['failure_response'] = array();
                $this->_Odb->_query("INSERT INTO services VALUES
                (NULL,
                '".$_Smethod."',
                '".$_Surl."',
                '".json_encode($_Adata['request'])."',
                '".json_encode($_Adata['response'])."',
                '".json_encode($_Adata['failure_response'])."')");
            }
        }
        return true;
    }
}