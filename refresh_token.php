<?php

include 'sageone_client.php';
include 'sageone_constants.php';

$apiClient = new SageoneClient($auth_endpoint, $token_endpoint, $scope);

/* Exchange the authorisation code for an access_token */
$response = $apiClient->renewAccessToken();

/* redirect with the response */
header("Location: http://" . $_SERVER['HTTP_HOST'] . "/");

?>
