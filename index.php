<!DOCTYPE html>
<?php
include 'sageone_client.php';
include 'sageone_constants.php';

$sageone_client = new SageoneClient($client_id, $client_secret, $callback_url, $auth_endpoint, $token_endpoint, $scope);

/* get the redirect url for authorisation */
$redirect_url = $sageone_client->authRedirect();
?>
<html>
  <head>
    <title>Authorize with Sage One</title>
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
    <a id="logo" href="/SageOneSampleApp">Sage One API Sample App</a>
  </div>
</header>
  <div class="container">
    <div class="center well">
      <h1>Sage One API Sample Application (PHP)</h1>
      <h3>This sample application integrates with the Sage One API.</h3>
      <p>Before you can make any API calls, you need to <a href="<?php echo $redirect_url ?>">Authorize</a><p>
    </div>
    </div>
  </body>
</html>
