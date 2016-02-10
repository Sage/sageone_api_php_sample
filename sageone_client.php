<?php

class SageoneClient {
  private $client_id;
	private $client_secret;
	private $callback_url;
	private $auth_endpoint;
	private $token_endpoint;
	private $scope;

	public function __construct($client_id, $client_secret, $callback_url, $auth_endpoint, $token_endpoint, $scope) {
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->callback_url = $callback_url;
		$this->auth_endpoint = $auth_endpoint;
		$this->token_endpoint = $token_endpoint;
		$this->scope = $scope;
	}

  public function authRedirect()
  {
    return  $this->auth_endpoint . "?response_type=code&client_id=" . $this->client_id . "&redirect_uri=" . $this->callback_url . "&scope=" . $this->scope;
  }

  public function getAccessToken($code) {
    $params = array("client_id" => $this->client_id,
                    "client_secret" => $this->client_secret,
                    "code" => $code,
                    "grant_type" => "authorization_code",
                    "redirect_uri" => $this->callback_url);

    $response = $this->getToken($params);
    return $response;
  }

  public function renewAccessToken($refresh_token) {
    $params = array("client_id" => $this->client_id,
                    "client_secret" => $this->client_secret,
                    "refresh_token" => $refresh_token,
                    "grant_type" => "refresh_token");

    $response = $this->getToken($params);
    return $response;
  }

  public function getData($endpoint, $header) {
    $options = array('http'=>array('method'=>"GET", 'header'=> $header));
    $context  = stream_context_create($options);
    $result = file_get_contents($endpoint, false, $context);
    if ($result === FALSE) { /* Handle error */ }

    return $result;
  }

  public function postData($endpoint, $params, $header) {
    $options = array('http' => array('method' => "POST", 'header'=> $header, 'content' => http_build_query($params)));
    $context  = stream_context_create($options);
    $result = file_get_contents($endpoint, false, $context);
    if ($result === FALSE) { /* Handle error */ }

    return $result;
  }

  private function getToken($params) {
    $url = $this->token_endpoint;
    $options = array('http' => array('header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                     'method'  => 'POST',
                                     'content' => http_build_query($params)));
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { /* Handle error */ }

    return $result;
  }
}

?>
