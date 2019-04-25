<!DOCTYPE html>
<?php
include 'sageone_constants.php';
include 'sageone_client.php';

$sageone_client = new SageoneClient($client_id, $client_secret, $callback_url, $auth_endpoint, $token_endpoint, $scope);
$header = array("Accept: *.*",
                "Content-Type: application/json",
                "User-Agent: Sage Accounting PHP Sample Application");

if($_GET) {
  switch(array_keys($_GET)[0]) {
    case "get_endpoint":
      $token = $_GET['get_access_token'];
      $sageone_guid = $_GET['get_resource_owner_id'];
      $endpoint = $_GET['get_endpoint'];
      $url = $base_endpoint . $endpoint;

      /* body params are empty for a GET request */
      $params = "";

      /* add the token, signature and nonce to the request header */
      array_push($header, "Authorization: Bearer " . $token, "X-Business: " . $sageone_guid);

      $response = $sageone_client->getData($url, $header);
      break;

    case "delete_endpoint":
      $token = $_GET['delete_access_token'];
      $sageone_guid = $_GET['delete_resource_owner_id'];
      $endpoint = $_GET['delete_endpoint'];
      $url = $base_endpoint . $endpoint;

      /* body params are empty for a DELETE request */
      $params = array();

      /* add the token, signature and nonce to the request header */
      array_push($header, "Authorization: Bearer " . $token, "X-Business: " . $sageone_guid);

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

      /* add the token, signature and nonce to the request header */
      array_push($header, "Authorization: Bearer " . $token, "X-Business: " . $sageone_guid);

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

  /* add the token, signature and nonce to the request header */
  array_push($header, "Authorization: Bearer " . $token, "X-Business: " . $sageone_guid);

  $response = $sageone_client->postData($url, $post_data_json_string, $header);
}

/* prettify JSON response for readability */
$json = json_decode($response);
$pretty_json = json_encode($json, JSON_PRETTY_PRINT);
?>
<html>
  <head>
    <title>Sage Accounting Response</title>
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
        <a id="logo" href="/">Sage Accounting API Sample App</a>
      </div>
    </header>
    <div class="container">
      <pre><?php echo $pretty_json; ?></pre>
    </div>
  </body>
</html>
