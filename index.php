<?php

include 'sageone_client.php';
include 'sageone_constants.php';

$client_config = new ClientConfiguration;
if ($client_config->fileExists() && !$client_config->load()) {
  $error = file_get_contents('views/error_loading_client_config.php');
}

include('views/header.php');

include('views/guide.php');

include('views/access_token.php');

if ($error) echo $error;

include('views/dev_resources.php');

include('views/footer.php');

?>
