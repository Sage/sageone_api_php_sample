<?php

include 'client_configuration.php';
include 'access_token_store.php';
include 'sage_accounting_api_response.php';

class SageoneClient {
  private $clientId;
  private $clientSecret;
  private $callbackUrl;
  private $scope;
  private $accessToken;
  private $refreshToken;
  private $accessTokenStore;

  const BASE_ENDPOINT = "https://api.accounting.sage.com/v3.1/";
  const AUTH_ENDPOINT = "https://www.sageone.com/oauth2/auth/central?filter=apiv3.1";
  const TOKEN_ENDPOINT = "https://oauth.accounting.sage.com/token";
  const SCOPE = "full_access";

  /**
  * Constructor
  */
  public function __construct() {
    $this->loadClientConfiguration();
  }

  /**
  * Returns the authorization endpoint with all required query params for
  * making the auth request
  */
  public function authorizationEndpoint() {
    return self::AUTH_ENDPOINT . "&response_type=code&client_id=" .
      $this->clientId . "&redirect_uri=" . $this->callbackUrl .
      "&scope=" . self::SCOPE . "&state=some_random_string";
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
    $params = array("client_id" => $this->clientId,
                    "client_secret" => $this->clientSecret,
                    "refresh_token" => $this->getRefreshToken(),
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

  /**
  * Returns the previously loaded access token
  */
  public function getAccessToken() {
    return $this->getAccessTokenStore()->getAccessToken();
  }

  /**
  * Returns the previously loaded UNIX timestamp when the access token expires
  */
  public function getExpiresAt() {
    return $this->getAccessTokenStore()->getExpiresAt();
  }

  /**
  * Returns the previously loaded refresh token
  */
  public function getRefreshToken() {
    return $this->getAccessTokenStore()->getRefreshToken();
  }

  public function getAccessTokenStore() {
    if ($this->accessTokenStore) return $this->accessTokenStore;

    $this->accessTokenStore = new AccessTokenStore();
    if (!$this->accessTokenStore->load()) {
      $this->accessTokenStore = NULL;
    }

    return $this->accessTokenStore;
  }

  // Private area

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
    $options = array('http' => array('header'  => "Content-type: application/x-www-form-urlencoded",
                                     'method'  => 'POST',
                                     'content' => http_build_query($params)));
    $context  = stream_context_create($options);
    $result = file_get_contents(self::TOKEN_ENDPOINT, false, $context);
    if ($result === FALSE) { /* Handle error */ }

    return $result;
  }

  private function getRequestHeaders() {
    return array("Accept: *.*",
                 "Content-Type: application/json",
                 "User-Agent: Sage Accounting API Sample App - PHP",
                 "Authorization: Bearer " . $this->getAccessToken());
  }

  private function loadClientConfiguration() {
    $clientConfig = new ClientConfiguration;
    if ($clientConfig->load()) {
      $this->clientId = $clientConfig->getClientId();
      $this->clientSecret = $clientConfig->getClientSecret();
      $this->callbackUrl = $clientConfig->getCallbackUrl();
    }
  }

  private function storeAccessToken($response) {
    $json = json_decode($response, true);

    if (!$this->accessTokenStore) {
      $this->accessTokenStore = new AccessTokenStore();
    }

    $this->accessTokenStore->save($json["access_token"],
                                  $json["expires_in"],
                                  $json["refresh_token"],
                                  $json["refresh_token_expires_in"]);

    return $json;
  }
}

?>
