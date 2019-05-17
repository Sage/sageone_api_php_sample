<?php

session_start();

include "lib/sage_accounting_api_client.php";

$apiClient = new SageAccountingApiClient;

$clientConfig = new ClientConfiguration;
if ($clientConfig->fileExists() && !$clientConfig->load()) {
  $error = file_get_contents('views/error_loading_client_config.php');
}

include('views/header.php');

include('views/guide.php');

if ($error) echo $error;

include('views/access_token.php');

include('views/api_response.php');

include('views/api_request.php');

include('views/dev_resources.php');

include('views/footer.php');

?>
