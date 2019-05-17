<?php
include 'sageone_constants.php';
include 'sageone_client.php';

session_start();
unset($_SESSION['api_response']);

$apiClient = new SageoneClient($auth_endpoint, $token_endpoint, $scope);

$response = $apiClient->execGET($_POST['resource']);

$_SESSION['api_response'] = serialize($response);

header("Location: http://" . $_SERVER['HTTP_HOST'] . "/");

?>
