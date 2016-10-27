<?php

class SageoneClient {
  private $client_id;
  private $client_secret;
  private $callback_url;
  private $auth_endpoint;
  private $token_endpoint;
  private $scope;

  /**
  * @param string $client_id Your application's client_id
  * @param string $client_secret Your application's client_secret
  * @param string $callback_url Your application's callback_url
  * @param string $auth_endpoint The authorisation endpoint (https://www.sageone.com/oauth2/auth)
  * @param string $token_endpoint The token endpoint (https://api.sageone.com/oauth2/token)
  * @param string $scope The type of access - readonly or full_access
  */
  public function __construct($client_id, $client_secret, $callback_url, $auth_endpoint, $token_endpoint, $scope) {
    $this->client_id = $client_id;
    $this->client_secret = $client_secret;
    $this->callback_url = $callback_url;
    $this->auth_endpoint = $auth_endpoint;
    $this->token_endpoint = $token_endpoint;
    $this->scope = $scope;
  }

  /**
  * Returns the redirect url with required query params for hitting the
  * authorisation endpoint
  */
  public function authRedirect() {
    return  $this->auth_endpoint . "?response_type=code&client_id=" . $this->client_id . "&redirect_uri=" . $this->callback_url . "&scope=" . $this->scope;
  }

  /* POST request to exchange the authorisation code for an access_token */
  public function getAccessToken($code) {
    $params = array("client_id" => $this->client_id,
                    "client_secret" => $this->client_secret,
                    "code" => $code,
                    "grant_type" => "authorization_code",
                    "redirect_uri" => $this->callback_url);

    $response = $this->getToken($params);
    return $response;
  }

  /* POST request to renew the access_token */
  public function renewAccessToken($refresh_token) {
    $params = array("client_id" => $this->client_id,
                    "client_secret" => $this->client_secret,
                    "refresh_token" => $refresh_token,
                    "grant_type" => "refresh_token");

    $response = $this->getToken($params);
    return $response;
  }

  /* GET request */
  public function getData($endpoint, $header) {
    $curl = curl_init($endpoint);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    $response = curl_exec($curl);
    if (!$response) { /* Handle error */ }

    return $response;
  }

  /* POST request */
  public function postData($endpoint, $post_data, $header) {
    $curl = curl_init($endpoint);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    $response = curl_exec($curl);
    if (!$response) { /* Handle error */ }

    return $response;
  }

  /* PUT request */
  public function putData($endpoint, $put_data, $header) {
    $curl = curl_init($endpoint);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $put_data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    $response = curl_exec($curl);
    if (!$response) { /* Handle error */ }

    return $response;
  }

  /* DELETE request */
  public function deleteData($endpoint, $header) {
    $curl = curl_init($endpoint);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    $response = curl_exec($curl);
    if (!$response) { /* Handle error */ }

    return $response;
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
