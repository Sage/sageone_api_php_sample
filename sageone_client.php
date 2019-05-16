<?php

include 'client_configuration.php';
include 'access_token_store.php';

class SageoneClient {
  private $clientId;
  private $clientSecret;
  private $callbackUrl;
  private $auth_endpoint;
  private $token_endpoint;
  private $scope;
  private $accessToken;
  private $refreshToken;

  /**
  * @param string $auth_endpoint The authorisation endpoint (https://www.sageone.com/oauth2/auth)
  * @param string $token_endpoint The token endpoint (https://api.sageone.com/oauth2/token)
  * @param string $scope The type of access - readonly or full_access
  */
  public function __construct($auth_endpoint, $token_endpoint, $scope) {
    $this->auth_endpoint = $auth_endpoint;
    $this->token_endpoint = $token_endpoint;
    $this->scope = $scope;
    $this->loadClientConfiguration();
  }

  /**
  * Returns the authorization endpoint with all required query params for
  * making the auth request
  */
  public function authorizationEndpoint() {
    return  $this->auth_endpoint . "&response_type=code&client_id=" .
      $this->clientId . "&redirect_uri=" . $this->callbackUrl .
      "&scope=" . $this->scope . "&state=some_random_string";
  }

  /* POST request to exchange the authorization code for an access_token */
  public function getInitialAccessToken($code) {
    $params = array("client_id" => $this->clientId,
                    "client_secret" => $this->clientSecret,
                    "code" => $code,
                    "grant_type" => "authorization_code",
                    "redirect_uri" => $this->callbackUrl);

    $response = $this->getToken($params);

    return $this->storeAccessToken($response);
  }

  /* POST request to renew the access_token */
  public function renewAccessToken() {
    $this->loadAccessToken();
    $params = array("client_id" => $this->clientId,
                    "client_secret" => $this->clientSecret,
                    "refresh_token" => $this->refreshToken,
                    "grant_type" => "refresh_token");

    $response = $this->getToken($params);

    return $this->storeAccessToken($response);
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
    $options = array('http' => array('header'  => "Content-type: application/x-www-form-urlencoded",
                                     'method'  => 'POST',
                                     'content' => http_build_query($params)));
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { /* Handle error */ }

    return $result;
  }

  private function loadClientConfiguration() {
    $client_config = new ClientConfiguration;
    if ($client_config->load()) {
      $this->clientId = $client_config->getClientId();
      $this->clientSecret = $client_config->getClientSecret();
      $this->callbackUrl = $client_config->getCallbackUrl();
    }
  }

  private function loadAccessToken() {
    $access_token_store = new AccessTokenStore();
    if ($access_token_store->load()) {
      $this->accessToken = $access_token_store->getAccessToken();
      $this->refreshToken = $access_token_store->getRefreshToken();
    }
  }

  private function storeAccessToken($response) {
    $json = json_decode($response, true);

    $access_token_store = new AccessTokenStore();
    $access_token_store->save($json["access_token"],
                              $json["expires_in"],
                              $json["refresh_token"],
                              $json["refresh_token_expires_in"]);

      return $json;
  }
}

?>
