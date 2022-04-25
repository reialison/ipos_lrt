<?php

namespace PayMaya\Core;
use Exception;

class HTTPConnection
{
	private $httpConfig;

	public function __construct($httpConfig)
	{
		if (!function_exists("curl_init")) {
			throw new Exception("curl module is not available in this machine");
		}
		$this->httpConfig = $httpConfig;
	}

	public function execute($data)
	{
		$session = curl_init($this->httpConfig->getUrl());
		curl_setopt_array($session, $this->httpConfig->getCurlOptions());
		curl_setopt($session, CURLOPT_URL, $this->httpConfig->getUrl());
		curl_setopt($session, CURLOPT_HEADER, true);
		curl_setopt($session, CURLINFO_HEADER_OUT, true);
		curl_setopt($session, CURLOPT_HTTPHEADER, $this->httpConfig->getHttpHeaders());

		// echo "<pre>qwer: ",print_r($this->httpConfig->getCurlOptions()),"</pre>";die();
		// echo "<pre>",print_r($data)," : test</pre>";die();
		switch ($this->httpConfig->getMethod()) {
			case "POST":
				curl_setopt($session, CURLOPT_POST, true);
				curl_setopt($session, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($session, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($session, CURLOPT_POSTFIELDS, $data);
				break;
			case "DELETE":
				curl_setopt($session, CURLOPT_CUSTOMREQUEST, "DELETE");
				curl_setopt($session, CURLOPT_POSTFIELDS, $data);
				break;
		}
		// echo $this->httpConfig->getUrl();die();

		if ($this->httpConfig->getMethod() != NULL) {
			curl_setopt($session, CURLOPT_CUSTOMREQUEST, $this->httpConfig->getMethod());
		}
		// echo "<pre>",print_r($session),"</pre>";die();
		$response = curl_exec($session);
		// var_dump($response);die();
		$httpStatus = curl_getinfo($session, CURLINFO_HTTP_CODE);
		// var_dump(curl_errno($session));die();
		// var_dump()
		if (curl_errno($session)) {      
		  throw new Exception(curl_error($session), curl_errno($session));

			// $exception = new Exception("Error API call");
			curl_close($session);
			throw $exception;
		}
		
		$responseHeaderSize = strlen($response) - curl_getinfo($session, CURLINFO_SIZE_DOWNLOAD);
		$result = substr($response, $responseHeaderSize);
		curl_close($session);
		// var_dump($result);die();
		return $result;
	}
}
