<?php

namespace ofi\olazada;
use ofi\olazada\lazada\LazopClient;
use ofi\olazada\lazada\LazopRequest;
use Exception;

/**
 * Class ini digunakan ketika user 
 * ingin menggunakan chaing request secara manual
 */

trait Client {

    protected $Client_APP_key = null;
    protected $Client_APP_Secret = null;
    protected $Client_region = null;
    protected $Client_Access_token = null;
    protected $Client_URL = null;
    protected $Client_API_Param_Key = [];
    protected $Client_API_Param_Value = [];

    public $instance = null;

    public static function CLIENT_DEBUG_ON()
    {
        error_reporting(0);
        ini_set('display_errors', 0);
        LazopClient::DEBUG_ON();
    }

    public static function CLIENT_DEBUG_OFF()
    {
        error_reporting(0);
        ini_set('display_errors', 0);
        LazopClient::DEBUG_OFF();
    }

    /**
     * To start use this manual request
     */

    public static function Manual()
    {
        $self = new self;
        $self->instance = true;
        return $self;
    }

    /**
     * To set API Endpoint by region
     */

    public function setRegion($value, $setManualURL = "")
    {
        if(empty($value)) {
            throw new Exception("You must set your region!");
        }

        $region = [
            'philippines' => 'https://api.lazada.com.ph/rest',
            'singapore' => "https://api.lazada.sg/rest",
            'vietnam' => "https://api.lazada.vn/rest",
            'thailand' => "https://api.lazada.co.th/rest",
            'indonesia' => "https://api.lazada.co.id/rest",
            "malaysia" => 'https://api.lazada.com.my/rest'
        ];

        if(!empty($setManualURL)) {
            $this->Client_region = $setManualURL;
        } else {
            // if(in_array(strtolower($value), $region)) {
                $this->Client_region = $region[strtolower($value)];
            // } 
        }

        return $this;
    }

    /**
     * To set App Key you can see in app console
     */

    public function setAppKey($appKey = '')
    {
        if(empty($appKey)) {
            throw new Exception("App key can't null");
        }

        $this->Client_APP_key = $appKey;
        return $this;
    }

    /**
     * To set App Secret, you can see in app console
     */

    public function setAppSecret($appSecret = '')
    {
        if(empty($appSecret)) {
            throw new Exception("App secret can't null");
        }

        $this->Client_APP_Secret = $appSecret;
        return $this;
    }

    /**
     * To set access token
     */

    public function setAccessToken($token = '')
    {
        if(empty($token)) {
            $this->Client_Access_token = null;
        }
        
        $this->Client_Access_token = $token;
        return $this;
    }

    /**
     * To define where do you url want to visit 
     * Lazada API with our system
     */

    public function setUrl($url)
    {
        if(empty($url)) {
            throw new Exception("You must define url");
        }

        $this->Client_URL = $url;
        return $this;
    }

    /**
     * To add API Param
     */

     public function addApiParam($key = '', $value = '')
     {
        //  Push key to $Client_API_Param_Key array
        array_push($this->Client_API_Param_Key, $key);

        // Push value to $Client_API_Param_Value array
        array_push($this->Client_API_Param_Value, $value);

        return $this;
     }

     /**
      * To get API Param as Array
      */

     public function getApiParam()
     {
         $APIPARAM = [];

         $key = $this->Client_API_Param_Key;
         $value = $this->Client_API_Param_Value;

         for ($i=0; $i < count($key) ; $i++) { 
             $APIPARAM[$key[$i]] = $value[$i];
         }

         return $APIPARAM;
     }

     /**
      * To execute this request
      */

     public function run($HTTP_Method = "GET")
     {
        return $this->runTheRequest(
            $this->Client_URL,
            $this->Client_region,
            $this->Client_APP_key,
            $this->Client_APP_Secret,
            $this->Client_Access_token,
            $this->getApiParam(),
            $HTTP_Method
         );
     }

     /**
      * To execute this request
      */

     public function go($HTTP_Method = "GET")
     {
        return $this->runTheRequest(
            $this->Client_URL,
            $this->Client_region,
            $this->Client_APP_key,
            $this->Client_APP_Secret,
            $this->Client_Access_token,
            $this->getApiParam(),
            $HTTP_Method
         );
     }

     public function runTheRequest(
         $APIURL,
         $CLIENTREGION,
         $APPKEY,
         $APPSECRET,
         $ACCESSTOKEN = null,
         $APIPARAM = [],
         $HTTPMETHOD
     )
     {
        $lazada = new LazopClient($CLIENTREGION, $APPKEY, $APPSECRET);
        $request = new LazopRequest($APIURL, $HTTPMETHOD);
        if(!empty($APIPARAM)) {
            foreach($APIPARAM as $key => $value) {
                $request->addApiParam($key, $value);
            }
        }

        $execute = $lazada->execute($request, $ACCESSTOKEN);
        return $execute;
     }

}