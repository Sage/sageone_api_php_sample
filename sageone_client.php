<?php

include 'client_configuration.php';
include 'access_token_store.php';
include 'sage_accounting_api_response.php';

class SageoneClient {
  private $clientId;
  private $clientSecret;
  private $callbackUrl;
  private $auth_endpoint;
  private $token_endpoint;
  private $scope;
  private $accessToken;
  private $refreshToken;

  const BASE_ENDPOINT = "https://api.accounting.sage.com/v3.1/";

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
  public function execApiRequest($resource, $httpMethod, $postData = NULL) {
    $curl = $this->prepareApiRequest($resource, $httpMethod, $postData);
    $response = $this->sendApiRequest($curl);

    return $this->buildApiResponse($curl, $response);
  }

  private function prepareApiRequest($resource, $method, $postData = NULL) {
    $endpoint = self::BASE_ENDPOINT . $resource;
    $method = strtoupper($method);

    $curl = curl_init($endpoint);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getRequestHeaders());

    if ($postData && ($method == 'POST' || $method == 'PUT')) {
      curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
    }

    curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);

    return $curl;
  }

  private function sendApiRequest($curl) {
    $response = curl_exec($curl);

    if (!$response) {
      /* Handle errors: DNS lookup failed, connection timeout, read timeout */
    }

    return $response;
  }

  private function buildApiResponse($curl, $response) {
    return new SageAccountingApiResponse($curl, $response);
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

  private function getRequestHeaders() {
    $this->loadAccessToken();

    return array("Accept: *.*",
                 "Content-Type: application/json",
                 "User-Agent: Sage Accounting API Sample App - PHP",
                 "Authorization: Bearer " . $this->accessToken);
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
