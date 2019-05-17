<?php

include 'sageone_client.php';

$apiClient = new SageoneClient;

/* Exchange the authorisation code for an access_token */
$response = $apiClient->getInitialAccessToken($_GET['code']);

/* redirect with the response */
header("Location: http://" . $_SERVER['HTTP_HOST'] . "/");

?>
