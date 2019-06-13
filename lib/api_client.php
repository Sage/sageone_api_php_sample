<?php
namespace SageAccounting;

require '/var/php/vendor/autoload.php';
include 'client_configuration.php';
include 'access_token_store.php';
include 'api_response.php';

class ApiClient
{
    private $clientId;
    private $clientSecret;
    private $callbackUrl;
    private $oauthClient;
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
    public function __construct()
    {
        $this->loadClientConfiguration();
        $this->$oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId' => $this->clientId,
        'clientSecret' => $this->clientSecret,
        'redirectUri' => $this->callbackUrl,
        'urlAuthorize' => self::AUTH_ENDPOINT,
        'urlAccessToken' => self::TOKEN_ENDPOINT,
        'urlResourceOwnerDetails' => ''
        ]);
    }

  /**
  * Returns the authorization endpoint with all required query params for
  * making the auth request
  */
    public function authorizationEndpoint()
    {
        return self::AUTH_ENDPOINT . "&response_type=code&client_id=" .
        $this->clientId . "&redirect_uri=" . $this->callbackUrl .
        "&scope=" . self::SCOPE . "&state=some_random_string";
    }

  /* POST request to exchange the authorization code for an access_token */
    public function getInitialAccessToken($code)
    {
        $initialAccessToken = $this->$oauthClient->getAccessToken('authorization_code', ['code' => $code]);

        return $this->storeAccessToken($initialAccessToken);
    }

  /* POST request to renew the access_token */
    public function renewAccessToken()
    {
        $newAccessToken = $this->$oauthClient->getAccessToken('refresh_token', ['refresh_token' => $this->getRefreshToken()]);

        return $this->storeAccessToken($newAccessToken);
    }

  /* GET request */
    public function execApiRequest($resource, $httpMethod, $postData = null)
    {
        $method = strtoupper($httpMethod);
        $options['headers']['Content-Type'] = 'application/json';

        if ($postData && ($method == 'POST' || $method == 'PUT')) {
            $options['body'] = $postData;
        }

        $request = $this->$oauthClient->getAuthenticatedRequest($method, self::BASE_ENDPOINT . $resource, $this->getAccessToken(), $options);

        $startTime = microtime(1);
        $requestResponse = $this->$oauthClient->getResponse($request);
        $endTime = microtime(1);

        return new \SageAccounting\ApiResponse($requestResponse, $endTime - $startTime);
    }

  /**
  * Returns the previously loaded access token
  */
    public function getAccessToken()
    {
        return $this->getAccessTokenStore()->getAccessToken();
    }

  /**
  * Returns the previously loaded UNIX timestamp when the access token expires
  */
    public function getExpiresAt()
    {
        return $this->getAccessTokenStore()->getExpiresAt();
    }

  /**
  * Returns the previously loaded refresh token
  */
    public function getRefreshToken()
    {
        return $this->getAccessTokenStore()->getRefreshToken();
    }

    public function getAccessTokenStore()
    {
        if ($this->accessTokenStore) {
            return $this->accessTokenStore;
        }

        $this->accessTokenStore = new \SageAccounting\AccessTokenStore();
        if (!$this->accessTokenStore->load()) {
            $this->accessTokenStore = null;
        }

        return $this->accessTokenStore;
    }

  // Private area
    private function loadClientConfiguration()
    {
        $clientConfig = new \SageAccounting\ClientConfiguration;
        if ($clientConfig->load()) {
            $this->clientId = $clientConfig->getClientId();
            $this->clientSecret = $clientConfig->getClientSecret();
            $this->callbackUrl = $clientConfig->getCallbackUrl();
        }
    }

    private function storeAccessToken($response)
    {
        if (!$this->accessTokenStore) {
            $this->accessTokenStore = new \SageAccounting\AccessTokenStore();
        }

        $this->accessTokenStore->save(
            $response->getToken(),
            $response->getExpires(),
            $response->getRefreshToken(),
            $response->getValues()["refresh_token_expires_in"]
        );
        return $response;
    }
}
