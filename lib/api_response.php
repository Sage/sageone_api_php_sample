<?php
namespace SageAccounting;

class ApiResponse
{
    private $status;
    private $headers;
    private $rawBody;
    private $json;
    private $duration;

    public function __construct($response, $duration)
    {
        $this->rawBody = $response->getBody()->getContents();
        $this->status = $response->getStatusCode();
        $this->headers = $response->getHeaders();
        $this->duration = round($duration, 3);
    }

  /**
  * Returns the response body as it was returned from the API
  */
    public function getBody()
    {
        return $this->rawBody;
    }

  /**
  * Returns the JSON parsed response body
  */
    public function getJSON()
    {
        if ($this->json) {
            return $this->json;
        }

        $this->json = @json_decode($this->rawBody);

        return $this->json;
    }

  /**
  * Returns the HTTP status code of the response
  */
    public function getStatus()
    {
        return $this->status;
    }

  /**
  * Returns the HTTP headers of the response
  */
    public function getHeaders()
    {
        return $this->headers;
    }

  /**
  * Returns the duration in seconds of the whole request
  */
    public function getDuration()
    {
        return $this->duration;
    }
}
