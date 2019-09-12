<?php

include "lib/api_client.php";

session_start();
unset($_SESSION['api_response']);

$apiClient = new \SageAccounting\ApiClient;

$response = $apiClient->execApiRequest(
    $_POST['resource'],
    $_POST['http_verb'],
    $_POST['post_data']
);

$_SESSION['api_response'] = serialize($response);

header("Location: http://" . $_SERVER['HTTP_HOST'] . "/");
