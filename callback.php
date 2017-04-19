<?php

include 'sageone_client.php';
include 'sageone_constants.php';

$country = $_GET['country'];

switch($country) {
  case "CA": $token_endpoint = $ca_token_endpoint; break;
  case "US": $token_endpoint = $us_token_endpoint; break;
  case "IE": case "GB": $token_endpoint = $uki_token_endpoint; break;
  default: $token_endpoint = $uki_token_endpoint; break;
}

$sageone_client = new SageoneClient($client_id, $client_secret, $callback_url, $auth_endpoint, $token_endpoint, $scope);

/* Exchange the authorisation code for an access_token */
$response = $sageone_client->getAccessToken($_GET['code']);

/* redirect with the response */
header("Location: http://localhost:8080/sageone_data.php?token_response=" . $response ."&country=" . $country);
die();

?>
