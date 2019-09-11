<?php

session_start();

include 'lib/api_client.php';

$apiClient = new \SageAccounting\ApiClient;

$clientConfig = new \SageAccounting\ClientConfiguration;
if ($clientConfig->fileExists() && !$clientConfig->load()) {
    $error = file_get_contents('views/error_loading_client_config.php');
}

include('views/header.php');

include('views/guide.php');

if ($error) {
    echo $error;
}

include('views/api_request.php');

include('views/api_response.php');

include('views/footer.php');
