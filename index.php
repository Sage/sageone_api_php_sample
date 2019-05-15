<?php

include 'sageone_client.php';
include 'sageone_constants.php';

$client_config = new ClientConfiguration;
if ($client_config->fileExists() && !$client_config->load()) {
  $error = file_get_contents('views/error_loading_client_config.php');
}

$sageone_client = new SageoneClient($client_id, $client_secret, $callback_url, $auth_endpoint, $token_endpoint, $scope);

/* get the redirect url for authorisation */
$redirect_url = $sageone_client->authRedirect();

include('views/header.php');

include('views/guide.php');

if ($error) echo $error;

include('views/dev_resources.php');

include('views/footer.php');

?>
