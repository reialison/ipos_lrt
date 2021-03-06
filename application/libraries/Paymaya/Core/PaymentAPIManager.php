<?php

namespace PayMaya\Core;

use PayMaya\PayMayaSDK;
use PayMaya\Core\HTTPConfig;
use PayMaya\Core\HTTPConnection;
use PayMaya\Core\Constants;

class PaymentAPIManager
{
	private $publicApiKey;
	private $secretApiKey;
	private $environment;
	private $baseUrl;
	private $httpHeaders;

	public function __construct()
	{
		$this->publicApiKey = PayMayaSDK::getInstance()->getCheckoutPublicApiKey();
		$this->secretApiKey = PayMayaSDK::getInstance()->getCheckoutSecretApiKey();
		$this->environment = PayMayaSDK::getInstance()->getCheckoutEnvironment();
		$this->baseUrl = $this->getBaseUrl();
				// echo "<pre>asd: ",print_r( $this->publicApiKey ),"</pre>";die();

		$this->httpHeaders = array("Content-Type" => "application/json");
	}

	private function getBaseUrl()
	{
		$baseUrl = null;
		switch ($this->environment) {
			case "PRODUCTION":
				$baseUrl = Constants::PAYMENTS_PRODUCTION_URL;
				break;
			default:
				$baseUrl = Constants::PAYMENTS_SANDBOX_URL;
		}
		return $baseUrl;
	}

	private function useBasicAuthWithApiKey($apiKey)
	{
		$authorizationToken = base64_encode($apiKey . ":");
		$this->httpHeaders["Authorization"] = "Basic " . $authorizationToken;
	}

	// Checkout

	public function initiatePayment($paymentInformation) 
	{
		// echo $this->baseUrl . "/v1/checkouts";die();
		$this->useBasicAuthWithApiKey($this->publicApiKey);
		$httpConfig = new HTTPConfig($this->baseUrl . "/v1/payments", 
									 "POST",
									 $this->httpHeaders
									 );
		$httpConnection = new HTTPConnection($httpConfig);
		$payload = json_encode($paymentInformation);
		// echo "<pre>",print_r($httpConfig),"</pre>";die();
		$response = $httpConnection->execute($payload);
		return $response;
	}

	public function retrievePayment($paymentId) 
	{
		$this->useBasicAuthWithApiKey($this->secretApiKey);
		$httpConfig = new HTTPConfig($this->baseUrl . "/v1/payments/" . $paymentId, 
									 "GET",
									 $this->httpHeaders
									 );
		$httpConnection = new HTTPConnection($httpConfig);
		$response = $httpConnection->execute(null);
		return $response;
	}

	// Customization

	public function setCustomization($customizationInformation)
	{
		$this->useBasicAuthWithApiKey($this->secretApiKey);
		$httpConfig = new HTTPConfig($this->baseUrl . "/v1/customizations", 
									 "POST",
									 $this->httpHeaders
									 );
		$httpConnection = new HTTPConnection($httpConfig);
		$payload = json_encode($customizationInformation);
		$response = $httpConnection->execute($payload);
		return $response;
	}

	public function getCustomization()
	{
		$this->useBasicAuthWithApiKey($this->secretApiKey);
		$httpConfig = new HTTPConfig($this->baseUrl . "/v1/customizations", 
									 "GET",
									 $this->httpHeaders
									 );
		$httpConnection = new HTTPConnection($httpConfig);
		$response = $httpConnection->execute(null);
		return $response;
	}

	public function removeCustomization()
	{
		$this->useBasicAuthWithApiKey($this->secretApiKey);
		$httpConfig = new HTTPConfig($this->baseUrl . "/v1/customizations", 
									 "DELETE",
									 $this->httpHeaders
									 );
		$httpConnection = new HTTPConnection($httpConfig);
		$response = $httpConnection->execute(null);
		return $response;
	}

	// Webhook

	public function retrieveWebhook()
	{
		$this->useBasicAuthWithApiKey($this->secretApiKey);
		$httpConfig = new HTTPConfig($this->baseUrl . "/v1/webhooks", 
									 "GET",
									 $this->httpHeaders
									 );
		$httpConnection = new HTTPConnection($httpConfig);
		$response = $httpConnection->execute(null);
		return $response;
	}

	public function registerWebhook($webhookInformation)
	{
		$this->useBasicAuthWithApiKey($this->secretApiKey);
		$httpConfig = new HTTPConfig($this->baseUrl . "/v1/webhooks", 
									 "POST",
									 $this->httpHeaders
									 );
		$httpConnection = new HTTPConnection($httpConfig);
		$payload = json_encode($webhookInformation);
		$response = $httpConnection->execute($payload);
		return $response;
	}

	public function updateWebhook($webhookId, $webhookInformation)
	{
		$this->useBasicAuthWithApiKey($this->secretApiKey);
		$httpConfig = new HTTPConfig($this->baseUrl . "/v1/webhooks/" . $webhookId, 
									 "PUT",
									 $this->httpHeaders
									 );
		$httpConnection = new HTTPConnection($httpConfig);
		$payload = json_encode($webhookInformation);
		$response = $httpConnection->execute($payload);
		return $response;
	}

	public function deleteWebhook($webhookId)
	{
		$this->useBasicAuthWithApiKey($this->secretApiKey);
		$httpConfig = new HTTPConfig($this->baseUrl . "/v1/webhooks/" . $webhookId, 
									 "DELETE",
									 $this->httpHeaders
									 );
		$httpConnection = new HTTPConnection($httpConfig);
		$response = $httpConnection->execute(null);
		return $response;
	}
}
