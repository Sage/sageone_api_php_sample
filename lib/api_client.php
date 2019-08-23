<?php
namespace SageAccounting;

require '/var/php/vendor/autoload.php';
include 'client_configuration.php';
include 'access_token_store.php';
include 'api_response.php';
include 'exception_handler.php';

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
    private $generatedState;

    const BASE_ENDPOINT = "https://api.accounting.sage.com/v3.1/";
    const AUTH_ENDPOINT = "https://www.sageone.com/oauth2/auth/central?filter=apiv3.1";
    const TOKEN_ENDPOINT = "https://oauth.accounting.sage.com/token";
    const SCOPE = "full_access";

  /**
  * Constructor
  */
    public function __construct()
    {
        $this->generateRandomState();
        $this->loadClientConfiguration();
        $this->$oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId' => $this->clientId,
        'clientSecret' => $this->clientSecret,
        'redirectUri' => $this->callbackUrl,
        'urlAuthorize' => self::AUTH_ENDPOINT,
        'urlAccessToken' => self::TOKEN_ENDPOINT,
        'urlResourceOwnerDetails' => '',
        'timeout' => 10
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
        "&scope=" . self::SCOPE . "&state=" . $this->generatedState;
    }

  /* POST request to exchange the authorization code for an access_token */
    public function getInitialAccessToken($code, $receivedState)
    {
        try {
          $initialAccessToken = $this->$oauthClient->getAccessToken('authorization_code', ['code' => $code]);
        }
        catch (\League\OAuth2\Client\Grant\Exception\InvalidGrantException $e) {
          // authorization code was not found or is invalid
          ExceptionHandler::raiseError(get_class($e), $e->getMessage());
        }  
        catch (\GuzzleHttp\Exception\ConnectException $e) {
          // if no internet connection is available
          ExceptionHandler::raiseError(get_class($e), $e->getMessage());
        }
        catch (UnexpectedValueException $e) {
          // An OAuth server error was encountered that did not contain a JSON body 
          ExceptionHandler::raiseError(get_class($e), $e->getMessage());
        }
        catch(Exception $e) {       
          // general exception
          ExceptionHandler::raiseError(get_class($e), $e->getMessage());
        } 
        finally {
          return $this->storeAccessToken($initialAccessToken);
        }
    }

  /* POST request to renew the access_token */
    public function renewAccessToken()
    {
      try {
          $newAccessToken = $this->$oauthClient->getAccessToken('refresh_token', ['refresh_token' => $this->getRefreshToken()]);
        }
      catch (\League\OAuth2\Client\Grant\Exception\InvalidGrantException $e) {
        // refresh token was not found or is invalid
        ExceptionHandler::raiseError(get_class($e), $e->getMessage());
      }  
      catch (\GuzzleHttp\Exception\ConnectException $e) {
        // if no internet connection is available
        ExceptionHandler::raiseError(get_class($e), $e->getMessage());
      }
      catch(Exception $e) {
        // general exception
        ExceptionHandler::raiseError(get_class($e), $e->getMessage());
      } 
      finally {
        return $this->storeAccessToken($newAccessToken);
      }
    }

  /* GET request */
    public function execApiRequest($resource, $httpMethod, $postData = null)
    {
        $method = strtoupper($httpMethod);
        $options['headers']['Content-Type'] = 'application/json';

        if ($postData && ($method == 'POST' || $method == 'PUT')) {
            $options['body'] = $postData;
        }

        try {
          $request = $this->$oauthClient->getAuthenticatedRequest($method, self::BASE_ENDPOINT . $resource, $this->getAccessToken(), $options);

          $startTime = microtime(1);
          $requestResponse = $this->$oauthClient->getResponse($request);

          } 
          catch(\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            ExceptionHandler::raiseError(get_class($e), $e->getMessage());
          } 
          catch (\GuzzleHttp\Exception\ClientException $e) {
            // catch all 4xx errors
            $requestResponse = $e->getResponse();
          }
          catch (\GuzzleHttp\Exception\ServerException $e) {
            // catch all 5xx errors
            $requestResponse = $e->getResponse();
          }
          catch (\GuzzleHttp\Exception\ConnectException $e) {
            // if no internet connection is available
            ExceptionHandler::raiseError(get_class($e), $e->getMessage());
          }
          catch(Exception $e) {    
            // general exception
            ExceptionHandler::raiseError(get_class($e), $e->getMessage());
          } 
          finally {
            $endTime = microtime(1);
            return new \SageAccounting\ApiResponse($requestResponse, $endTime - $startTime);            
          }
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

    private function generateRandomState() {
      $include_chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

      $charLength = strlen($include_chars);
      $randomString = '';
      // length of 30
      for ($i = 0; $i < 30; $i++) { 
          $randomString .= $include_chars [rand(0, $charLength - 1)];
      }
      $this->generatedState = $randomString;
  }   
}
