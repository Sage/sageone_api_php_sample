<?php
namespace SageAccounting;

class ClientConfiguration
{
    private $clientId;
    private $clientSecret;
    private $callbackUrl;

    const FILE_NAME = "config/client_application.yml";

  /**
  * Returns the previously loaded client Id
  */
    public function getClientId()
    {
        return $this->clientId;
    }

  /**
  * Returns the previously loaded client secret
  */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

  /**
  * Returns the previously loaded callback URL
  */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

  /**
  * Returns TRUE if the config file exists, otherwise FALSE
  */
    public function fileExists()
    {
        return file_exists(self::FILE_NAME);
    }

  /**
  * Loads the data from the config file. Returns TRUE on success, otherwise FALSE
  */
    public function load()
    {
        $result = @yaml_parse_file(self::FILE_NAME);
        if (!$result) {
            return false;
        }

        $this->clientId = $result['config']['client_id'];
        $this->clientSecret = $result['config']['client_secret'];
        $this->callbackUrl = $result['config']['callback_url'];

        return true;
    }
}
