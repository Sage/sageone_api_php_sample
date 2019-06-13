<?php

include "lib/api_client.php";

$apiClient = new \SageAccounting\ApiClient;

/* Exchange the authorisation code for an access_token */
$response = $apiClient->renewAccessToken();

/* redirect with the response */
header("Location: http://" . $_SERVER['HTTP_HOST'] . "/");
