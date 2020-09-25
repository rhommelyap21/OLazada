<?php 

namespace ofi\olazada;
use ofi\olazada\lazada\LazopClient;
use ofi\olazada\lazada\LazopRequest;
use Exception;
use ofi\olazada\Client;

/**
 * Define log folder
 */

define('LAZOP_SDK_WORK_DIR', dirname(__FILE__) . '/logs/');

/**
 * Start session
 */
session_start();

class OLazada extends LazopClient {

    // for manual chaining method request
    use Client;

    public $lazada;
    public $appkey;
    public $secretKey;
    public $auth_url = 'https://auth.lazada.com/oauth/authorize';
    public $api_url;
    public const Authorization_URL = "https://auth.lazada.com/rest";
    public $SERVER_URL;

    public function __construct(
        $appkey = '',
        $secretKey = '',
        $api_url = "https://api.lazada.co.id/rest"
    ) {

        // simpan data ke cookies juga
        // supaya bisa diambil lagi dengan mudah
        $this->saveAppKey($appkey);
        $this->saveSecretKey($secretKey);
        $this->saveServiceEndpoint($api_url);
        $this->SERVER_URL = $_SERVER['HTTP_HOST'];

        return $this;
    }

    public function saveAppKey($appkey)
    {
        // hapus dulu datanya
        if(isset($_COOKIE['OLaz_APP_KEY'])) {
            setcookie("OLaz_APP_KEY", "", time() - 3600);
        }

        // baru di save lagi
        setcookie(
            "OLaz_APP_KEY",
            $appkey,
            time() + (10 * 365 * 24 * 60 * 60),
            '/',
            $this->SERVER_URL
        );
        $this->appkey = $appkey;

        // save data ke sessi juga
        $_SESSION['OLaz_APP_KEY'] = $appkey;

        return true;
    }

    public function saveSecretKey($secretKey)
    {
        // hapus dulu datanya
        if(isset($_COOKIE['OLaz_APP_SECRET'])) {
            setcookie("OLaz_APP_SECRET", "", time() - 3600);
        }

        // baru disave lagi
        setcookie(
            "OLaz_APP_SECRET",
            $secretKey,
            time() + (10 * 365 * 24 * 60 * 60),
            '/',
            $this->SERVER_URL
        );

        $this->secretKey = $secretKey;

        // save data ke sessi juga
        $_SESSION['OLaz_APP_SECRET'] = $secretKey;

        return true;
    }

    public function saveServiceEndpoint($api_url)
    {
        // hapus dulu datanya
        if(isset($_COOKIE['OLaz_ServiceEndpoint'])) {
            setcookie("OLaz_ServiceEndpoint", "", time() - 3600);
        }

        // baru save lagi
        setcookie(
            "OLaz_ServiceEndpoint",
            $api_url,
            time() + (10 * 365 * 24 * 60 * 60),
            '/',
            $this->SERVER_URL
        );

        $this->api_url = $api_url;

        $_SESSION['OLaz_ServiceEndpoint'] = $api_url;

        return true;
    }

    public function getServiceEndpoint()
    {
        if(!empty($this->api_url)) {
        
            $api_url = $this->api_url;

        } else if(isset($_SESSION['OLaz_ServiceEndpoint'])) {
            
            $api_url = $_SESSION['OLaz_ServiceEndpoint'];

        } else {

            $api_url = $_COOKIE['OLaz_ServiceEndpoint'];
        }
 
        return $api_url;
    }

    public function getAppKey()
    {
        if(isset($_COOKIE['OLaz_APP_KEY'])) {

            return $_COOKIE['OLaz_APP_KEY'];

        } else if(isset($_SESSION['OLaz_APP_KEY'])) { {}

           return $_SESSION['OLaz_APP_KEY'];
           
        } else {

            return $this->appkey;
        }
    }

    public function getSecretKey()
    {
        if(isset($_COOKIE['OLaz_APP_SECRET'])) {
           
            return $_COOKIE['OLaz_APP_SECRET'];

        } else if(isset($_SESSION['OLaz_APP_SECRET'])) {
            
            return $_SESSION['OLaz_APP_SECRET'];

        } else {

            return $this->secretKey;
        }
    }

    /**
     * Send request to lazada
     * $api_url is Service Endpoints
     * $url is url what do you want to call from lazada server
     * $params is addApiParam 
     *    For example See this screenshoot
     *    https://freeimage.host/i/25ALbt
     * 
     * $access_token is access token from lazada
     * $method is HTTP Method what do you want
     */

    public static function Request($url = '', $params = [], $access_token = '', $method = 'GET')
    {
        $self = new self;

        $appkey = $self->getAppKey();
        $secretKey = $self->getSecretKey();
        $api_url = $self->getServiceEndpoint();

        if(empty($access_token)) {
            $access_token = $_COOKIE['OLaz_Access_token'];
        }

        $request = new LazopRequest($url, $method);
        if(!empty($params)) {
            foreach($params as $key => $value) {
                $request->addApiParam($key, $value);
            } 
        } 

        $LazopClient = new LazopClient($api_url, $appkey, $secretKey);
        return $LazopClient -> execute($request, $access_token);
    }

    /**
     * To turn on debuging
     */

    public static function DEBUG_ON()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        LazopClient::DEBUG_ON();
        LazopRequest::DEBUG_ON();
        self::CLIENT_DEBUG_ON();
        $self = new self;
        return $self;
    }

    /**
     * To turn off debuging
     */

    public static function DEBUG_OFF()
    {
        error_reporting(0);
        ini_set('display_errors', 0);
        LazopClient::DEBUG_OFF();
        LazopRequest::DEBUG_OFF();
        self::CLIENT_DEBUG_OFF();
        $self = new self;
        return $self;
    }

    /**
     * To print all configuration
     */

    public static function DEBUG_PRINT()
    {
        $self = new self;
        $debug = [
            'Service_Endpoint' => $self->getServiceEndpoint(),
            'App_key' => $self->getAppKey(),
            'App_Secret' => $self->getSecretKey(),
            'Server_URL' => $self->SERVER_URL
        ];

        echo "<h1> Print our all configuration </h1>";
        echo "<br>";

        foreach ($debug as $key => $value) {
            echo "My " . $key . ' is ' . $value . "<br>";
        }
    }

    /**
     * To get authorization code
     * after seller login with their account
     * 
     * $callback_url = The callback URL you provided when creating the application.
     * 
     */

    public static function authorization($callback_url, $client_id = '')
    {
        if(empty($callback_url)) {
            throw new Exception("Callback can't null!", 500);
        }

        $self = new self;

        if(empty($client_id)) {
            $client = $self->getAppKey();
        } else {
            $client = $client_id;
        }

        $query = http_build_query([
            'response_type' => 'code',
            'force_auth' => true,
            'redirect_uri' => $callback_url,
            'client_id' => $client
        ]);

        $Url = "https://auth.lazada.com/oauth/authorize?" . $query;

        return header('Location:' . $Url);
    }

    /**
     * To get authorization code
     * and use it to get the Access Token
     */

    public static function callback($appkeys = '', $secretKeys = '')
    {
        $code = $_GET['code'];
        if(empty($code)) {
            throw new Exception("Invalid code, code can't null", 404);
        }

        $self = new self;

        if(!empty($appkeys)) {
            $appkey = $appkeys;
        } else {
            $appkey = $self->getAppKey();
        }
        
        if(!empty($secretKeys)) {
            $secretKey = $secretKeys;
        } else {
            $secretKey = $self->getSecretKey();
        }

        $client = new LazopClient(self::Authorization_URL, $appkey, $secretKey);
        $request = new LazopRequest('/auth/token/create');
        $request->addApiParam('code', (String) $code);

        $response  = $client->execute($request);

        // save access token to cookie 
        $decode = json_decode($response, true);
        self::saveData('OLaz_Access_token', $decode['access_token']);

        return $response;
    }

    /**
     * To save data to cookie
     */

    public static function saveData($key = '', $value = '')
    {
        if(empty($key) || empty($value)) {
            throw new Exception("Key or value can't null!", 500);
        }

        setcookie(
            $key,
            $value,
            time() + (10 * 365 * 24 * 60 * 60)
        );

        return true;
    }

}