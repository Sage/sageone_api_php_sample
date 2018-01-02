<?php

include 'sageone_client.php';
include 'sageone_constants.php';

$country = $_GET['country'];

if ( isset( $endpoints['token'][$country] ) )
  $token_endpoint = $endpoints['token'][$country] ;
else {
  echo "Country endpoints not defined" ;
  exit ;
}

$sageone_client = new SageoneClient($client_id, $client_secret, $callback_url, $endpoints['auth'], $token_endpoint, $scope);

/* Exchange the authorisation code for an access_token */
$response = $sageone_client->getAccessToken($_GET['code']);

/* redirect with the response */
header("Location: http://localhost:8080/sageone_data.php?token_response=" . $response ."&country=" . $country);
die();

?>
