<?php
namespace SageAccounting;

class AccessTokenStore
{
    private $accessToken;
    private $expiresAt;
    private $refreshToken;
    private $refreshTokenExpiresAt;

    const FILE_NAME = "config/access_token.yml";

  /**
  * Returns the previously loaded access token
  */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

  /**
  * Returns the previously loaded UNIX timestamp when the access token expires
  */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

  /**
  * Returns the previously loaded refresh token
  */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

  /**
  * Returns the previously loaded UNIX timestamp when the refresh token expires
  */
    public function getRefreshTokenExpiresAt()
    {
        return $this->refreshTokenExpiresAt;
    }

  /**
  * Writes the data to the YAML file. Returns TRUE on success, otherwise FALSE
  */
    public function save($accessToken, $expiresAt, $refreshToken, $refreshTokenExpiresIn)
    {

        $data = array(
        "data" => array(
        "access_token" => $accessToken,
        "expires_at" => $expiresAt,
        "refresh_token" => $refreshToken,
        "refresh_token_expires_at" => time() + $refreshTokenExpiresIn
        )
        );

        $yaml = yaml_emit($data);
        $result = file_put_contents(self::FILE_NAME, $yaml);

        return $result ? true : false;
    }

  /**
  * Returns TRUE if the config file exists, otherwise FALSE
  */
    public function fileExists()
    {
        return file_exists(self::FILE_NAME);
    }

  /**
  * Loads the data from the YAML file. Returns TRUE on success, otherwise FALSE
  */
    public function load()
    {
        $result = @yaml_parse_file(self::FILE_NAME);
        if (!$result) {
            return false;
        }

        $this->accessToken = $result["data"]["access_token"];
        $this->expiresAt = $result["data"]["expires_at"];
        $this->refreshToken = $result["data"]["refresh_token"];
        $this->refreshTokenExpiresAt = $result["data"]["refresh_token_expires_at"];

        return true;
    }
}
