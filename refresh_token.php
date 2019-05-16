<?php

include 'sageone_client.php';
include 'sageone_constants.php';

$sageone_client = new SageoneClient($auth_endpoint, $token_endpoint, $scope);

/* Exchange the authorisation code for an access_token */
$response = $sageone_client->renewAccessToken();

/* redirect with the response */
header("Location: http://" . $_SERVER['HTTP_HOST'] . "/");

?>
