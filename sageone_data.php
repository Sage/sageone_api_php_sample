<!DOCTYPE html>
<?php
$access_token = $_GET['access_token'];
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
        <a id="logo" href="/">Sage One API Sample App</a>
      </div>
    </header>
    <div class="container">
      <div class='col-md-6 col-md-offset-3'>
        <h2>Successfully authenticated</h2>
        <p>Access Token:<code><?php echo $access_token ?></code></p>
        <div class="well">
          <h3>GET request</h3>
          <form action="sageone_response.php" method="get" class="endpoint_form">
            <label for="endpoint">Endpoint (e.g. accounts/v1/contacts):</label>
            <input id="endpoint" name="endpoint" type="text" value="" class="form-control" required="true">
            <input id="access_token" name="access_token" type="hidden" value="<?php echo $access_token ?>">
            <input type='submit' value='GET' class='btn btn-primary'>
          </form>
        </div>
        <div class="well">
          <h3>POST request</h3>
          <form action="sageone_response.php" method="post" class="endpoint_form">
            <label for="endpoint">Endpoint (e.g. accounts/v1/contacts):</label>
            <input id="post_endpoint" name="post_endpoint" type="text" value="" class="form-control" required="true">
              <label for="post_data">Post data</label>
              <textarea id="post_data" class="form-control" name="post_data"></textarea>
              <p>Example: {"contact[contact_type_id]": 1, "contact[name]": "Joe Bloggs"}</p>
            <input id="post_access_token" name="post_access_token" type="hidden" value="<?php echo $access_token ?>">
            <input type='submit' value='POST' class='btn btn-primary'>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
