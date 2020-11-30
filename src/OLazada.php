<?php 

namespace ofi\olazada;

require dirname(__DIR__) . '/vendor/autoload.php';

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
        $this->saveAppKey($appkey);
        $this->saveSecretKey($secretKey);
        $this->saveServiceEndpoint($api_url);
        $this->SERVER_URL = $_SERVER['HTTP_HOST'];

        return $this;
    }

    public function saveAppKey($appkey)
    {
        if(!defined('OLaz_APP_KEY')) {
            define('OLaz_APP_KEY', $appkey);
        }

        $this->appkey = $appkey;
        return true;
    }

    public function saveSecretKey($secretKey)
    {
        if(defined('OLaz_APP_SECRET')) {
            define('OLaz_APP_SECRET', $secretKey);
        }
        
        $this->secretKey = $secretKey;
        return true;
    }

    public function saveServiceEndpoint($api_url)
    {
        if(defined('OLaz_ServiceEndpoint')) {
            define('OLaz_ServiceEndpoint', $api_url);
        }
        
        $this->api_url = $api_url;
        return true;
    }

    /**
     * Get Service Endpoint
     */
    public function getServiceEndpoint()
    {
        $api_url = null;
        if(!empty($this->api_url)) {
            $api_url = $this->api_url;
        } else {
            $api_url = OLaz_ServiceEndpoint;
        }
 
        return $api_url;
    }

    /**
     * Get App Key
     */
    public function getAppKey(): String
    {
        $appkey = null;
        if(!empty($this->appkey)) {
            $appkey = $this->appkey;
        } else {
            $appkey = OLaz_APP_KEY;
        }

        return $appkey;
    }

    /**
     * Get Secret Key
     */
    public function getSecretKey(): String
    {
        $secretKey = null;
        if(!empty($this->secretKey)) {
            $secretKey = $this->secretKey;
        } else {
            $secretKey = OLaz_APP_SECRET;
        }

        return $secretKey;
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
            $access_token = base64_decode($_COOKIE['OLaz_Access_token']);
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
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
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

        if(empty($client) || empty($callback_url)) {
            throw new Exception("Client id or callback not found");
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
        if(isset($decode['access_token'])) {
            self::saveData('OLaz_Access_token', base64_encode($decode['access_token']));   
        } else {
            echo $response;
            die();
        }

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