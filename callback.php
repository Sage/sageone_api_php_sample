<?php

include "lib/api_client.php";

$apiClient = new \SageAccounting\ApiClient;

try {
  $response = $apiClient->getInitialAccessToken($_GET['code'],$_GET['state']);
} 
catch (BadMethodCallException $e) {
  // Required parameter not passed: "code"
  ExceptionHandler::raiseError(get_class($e), $e->getMessage());
}
catch (UnexpectedValueException $e) {
  // An OAuth server error was encountered that did not contain a JSON body 
  ExceptionHandler::raiseError(get_class($e), $e->getMessage());
}
finally {
  /* Exchange the authorisation code for an access_token */
  /* redirect with the response */
  header("Location: http://" . $_SERVER['HTTP_HOST'] . "/");
}

