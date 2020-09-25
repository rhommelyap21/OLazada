<?php

namespace ofi\olazada\lazada;
use Exception;

class LazopRequest
{
	public $apiName;

	public $headerParams = array();

	public $udfParams = array();

	public $fileParams = array();

	public $httpMethod = 'POST';

	public function __construct($apiName,$httpMethod = 'POST')
	{
		$this->apiName = $apiName;
		$this->httpMethod = $httpMethod;

		if($this->startWith($apiName,"//"))
		{
			throw new Exception("api name is invalid. It should be start with /");			
		}
	}

	/**
     * To turn on debuging
     */

    public static function DEBUG_ON()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    /**
     * To turn off debuging
     */

    public static function DEBUG_OFF()
    {
        error_reporting(0);
        ini_set('display_errors', 0);
    }


	function addApiParam($key,$value)
	{

		if(!is_string($key))
		{
			throw new Exception("api param key should be string");
		}

		if(is_object($value))
		{
			$this->udfParams[$key] = json_decode($value);
		}
		else
		{
			$this->udfParams[$key] = $value;
		}
	}

	function addFileParam($key,$content,$mimeType = 'application/octet-stream')
	{
		if(!is_string($key))
		{
			throw new Exception("api file param key should be string");
		}

		$file = array(
            'type' => $mimeType,
            'content' => $content,
            'name' => $key
        );
		$this->fileParams[$key] = $file;
	}

	function addHttpHeaderParam($key,$value)
	{
		if(!is_string($key))
		{
			throw new Exception("http header param key should be string");
		}

		if(!is_string($value))
		{
			throw new Exception("http header param value should be string");
		}

		$this->headerParams[$key] = $value;
	}

	function startWith($str, $needle) {
	    return strpos($str, $needle) === 0;
	}
}

?>