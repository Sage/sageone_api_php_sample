<!DOCTYPE html>
<?php
include 'sageone_constants.php';
include 'sageone_signer.php';
include 'sageone_client.php';

$country = $_GET['country'] ?: $_POST['country'];

switch($country) {
  case "CA":
    $base_endpoint = $ca_base_endpoint;
    $token_endpoint = $ca_token_endpoint;
    break;
  case "US":
    $base_endpoint = $us_base_endpoint;
    $token_endpoint = $us_token_endpoint;
    break;
  case "IE": case "GB":
    $base_endpoint = $uki_base_endpoint;
    $token_endpoint = $uki_token_endpoint;
    break;
  default:
    $base_endpoint = $uki_base_endpoint;
    $token_endpoint = $uki_token_endpoint;
    break;
};

$sageone_client = new SageoneClient($client_id, $client_secret, $callback_url, $auth_endpoint, $token_endpoint, $scope);
$nonce = bin2hex(openssl_random_pseudo_bytes(32));
$header = array("Accept: *.*",
                "Content-Type: application/json",
                "User-Agent: Sage One Sample Application",
                "ocp-apim-subscription-key: " . $apim_subscription_key);

if($_GET) {
  switch(array_keys($_GET)[0]) {
    case "get_endpoint":
      $token = $_GET['get_access_token'];
      $sageone_guid = $_GET['get_resource_owner_id'];
      $endpoint = $_GET['get_endpoint'];
      $url = $base_endpoint . $endpoint;

      /* body params are empty for a GET request */
      $params = "";

      /* generate the request signature */
      $signature_object = new SageoneSigner("get", $url, $params, $nonce, $signing_secret, $token, $sageone_guid);
      $signature = $signature_object->signature();

      /* add the token, signature and nonce to the request header */
      array_push($header, "Authorization: Bearer " . $token, "X-Signature: " . $signature, "X-Nonce: " . $nonce, "X-Site: " . $sageone_guid);

      $response = $sageone_client->getData($url, $header);
      break;

    case "delete_endpoint":
      $token = $_GET['delete_access_token'];
      $sageone_guid = $_GET['delete_resource_owner_id'];
      $endpoint = $_GET['delete_endpoint'];
      $url = $base_endpoint . $endpoint;

      /* body params are empty for a DELETE request */
      $params = array();

      /* generate the request signature */
      $signature_object = new SageoneSigner("delete", $url, $params, $nonce, $signing_secret, $token, $sageone_guid);
      $signature = $signature_object->signature();

      /* add the token, signature and nonce to the request header */
      array_push($header, "Authorization: Bearer " . $token, "X-Signature: " . $signature, "X-Nonce: " . $nonce, "X-Site: " . $sageone_guid);

      $response = $sageone_client->deleteData($url, $header);
      break;

    case "put_endpoint":
      $token = $_GET['put_access_token'];
      $sageone_guid = $_GET['put_resource_owner_id'];
      $endpoint = $_GET['put_endpoint'];
      $url = $base_endpoint . $endpoint;
      $put_data = utf8_encode($_GET['put_data']);

      /* get the body params as JSON */
      $put_data_json_array = json_decode($put_data, true);
      $put_data_json_string = json_encode($put_data_json_array);

      /* generate the request signature */
      $signature_object = new SageoneSigner("put", $url, $put_data_json_string, $nonce, $signing_secret, $token, $sageone_guid);
      $signature = $signature_object->signature();

      /* add the token, signature and nonce to the request header */
      array_push($header, "Authorization: Bearer " . $token, "X-Signature: " . $signature, "X-Nonce: " . $nonce, "X-Site: " . $sageone_guid);

      $response = $sageone_client->putData($url, $put_data_json_string, $header);
      break;
  }
} else {
  $token = $_POST['post_access_token'];
  $sageone_guid = $_POST['post_resource_owner_id'];
  $endpoint = $_POST['post_endpoint'];
  $url = $base_endpoint . $endpoint;
  $post_data = utf8_encode($_POST['post_data']);


  /* get the body params as JSON */
  $post_data_json_array = json_decode($post_data, true);
  $post_data_json_string = json_encode($post_data_json_array);

  /* generate the request signature */
  $signature_object = new SageoneSigner("post", $url, $post_data_json_string, $nonce, $signing_secret, $token, $sageone_guid);
  $signature = $signature_object->signature();

  /* add the token, signature and nonce to the request header */
  array_push($header, "Authorization: Bearer " . $token, "X-Signature: " . $signature, "X-Nonce: " . $nonce, "X-Site: " . $sageone_guid);

  $response = $sageone_client->postData($url, $post_data_json_string, $header);
}

/* prettify JSON response for readability */
$json = json_decode($response);
$pretty_json = json_encode($json, JSON_PRETTY_PRINT);
?>
<html>
  <head>
    <title>Sage One Response</title>
    <link type="text/css" rel="stylesheet" href="sample_app.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  </head>
  <body>
    <header class="navbar navbar-fixed-top navbar-inverse">
      <div class="container">
        <a id="logo" href="/">Sage One API Sample App</a>
      </div>
    </header>
    <div class="container">
      <pre><?php echo $pretty_json; ?></pre>
    </div>
  </body>
</html>
