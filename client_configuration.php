<?php

class ClientConfiguration {
  private $clientId;
  private $clientSecret;
  private $callbackUrl;

  /**
  * Returns the previously loaded client Id
  */
  public function getClientId() {
    return $this->clientId;
  }

  /**
  * Returns the previously loaded client secret
  */
  public function getClientSecret() {
    return $this->clientSecret;
  }

  /**
  * Returns the previously loaded callback URL
  */
  public function getCallbackUrl() {
    return $this->callbackUrl;
  }

  /**
  * Loads the data from the config file. Returns TRUE on success, otherwise FALSE
  */
  public function fileExists() {
    return file_exists("client_config.yml");
  }

  /**
  * Loads the data from the config file. Returns TRUE on success, otherwise FALSE
  */
  public function load() {
    $result = @yaml_parse_file("client_config.yml");
    if (!$result) return FALSE;

    $this->clientId = $result['config']['client_id'];
    $this->clientSecret = $result['config']['client_secret'];
    $this->callbackUrl = $result['config']['callback_url'];

    return TRUE;
  }
}

?>
