<!DOCTYPE html>
<?php
$response = $_GET['token_response'];
$access_token = json_decode($response, true)['access_token'];
$resource_owner_id = json_decode($response, true)['resource_owner_id'];
$country = $_GET['country'];
/* prettify JSON response for readability */
$json = json_decode($response);
$pretty_json = json_encode($json, JSON_PRETTY_PRINT);
?>
<html>
  <head>
    <title>Authorize with Sage One</title>
    <link type="text/css" rel="stylesheet" href="sample_app.css">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
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
        <pre><?php echo $pretty_json; ?></pre>
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#get" aria-controls="get" role="tab" data-toggle="tab">GET</a></li>
          <li role="presentation"><a href="#post" aria-controls="post" role="tab" data-toggle="tab">POST</a></li>
          <li role="presentation"><a href="#put" aria-controls="put" role="tab" data-toggle="tab">PUT</a></li>
          <li role="presentation"><a href="#delete" aria-controls="delete" role="tab" data-toggle="tab">DELETE</a></li>
        </ul>
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane fade in active" id="get">
              <h3>GET request</h3>
              <form action="sageone_response.php" method="get" class="endpoint_form">
                <label for="get_endpoint">Endpoint</label>
                <input id="get_endpoint" name="get_endpoint" type="text" value="" class="form-control" required="true">
                <p>Example: accounts/v3/contacts</p>
                <input id="get_access_token" name="get_access_token" type="hidden" value="<?php echo $access_token ?>">
                <input id="get_resource_owner_id" name="get_resource_owner_id" type="hidden" value="<?php echo $resource_owner_id ?>">
                <input id="country" name="country" type="hidden" value="<?php echo $country ?>">
                <input type='submit' value='GET' class='btn btn-primary'>
              </form>
          </div>
          <div role="tabpanel" class="tab-pane fade" id="post">
            <h3>POST request</h3>
            <form action="sageone_response.php" method="post" class="endpoint_form">
              <label for="post_endpoint">Endpoint</label>
              <input id="post_endpoint" name="post_endpoint" type="text" value="" class="form-control" required="true">
              <p>Example: accounts/v3/contacts</p>
              <label for="post_data">Post data</label>
              <textarea id="post_data" class="form-control" name="post_data"></textarea>
              <p>Example: {"contact": { "contact_type_ids": ["CUSTOMER"], "name": "Joe Bloggs"}}</p>
              <input id="post_access_token" name="post_access_token" type="hidden" value="<?php echo $access_token ?>">
              <input id="post_resource_owner_id" name="post_resource_owner_id" type="hidden" value="<?php echo $resource_owner_id ?>">
              <input id="country" name="country" type="hidden" value="<?php echo $country ?>">
              <input type='submit' value='POST' class='btn btn-primary'>
            </form>
          </div>
          <div role="tabpanel" class="tab-pane fade" id="put">
            <h3>PUT request</h3>
            <form action="sageone_response.php" method="put" class="endpoint_form">
              <label for="put_endpoint">Endpoint</label>
              <input id="put_endpoint" name="put_endpoint" type="text" value="" class="form-control" required="true">
              <p>Example: accounts/v3/contacts/:id</p>
              <label for="put_data">Put data</label>
              <textarea id="put_data" class="form-control" name="put_data"></textarea>
              <p>Example: {"contact": { "name": "My New Name"}}</p>
              <input id="put_access_token" name="put_access_token" type="hidden" value="<?php echo $access_token ?>">
              <input id="put_resource_owner_id" name="put_resource_owner_id" type="hidden" value="<?php echo $resource_owner_id ?>">
              <input id="country" name="country" type="hidden" value="<?php echo $country ?>">
              <input type='submit' value='PUT' class='btn btn-primary'>
            </form>
          </div>
          <div role="tabpanel" class="tab-pane fade" id="delete">
            <h3>DELETE request</h3>
            <form action="sageone_response.php" method="delete" class="endpoint_form">
              <label for="delete_endpoint">Endpoint</label>
              <input id="delete_endpoint" name="delete_endpoint" type="text" value="" class="form-control" required="true">
              <p>Example: accounts/v3/contacts/:id</p>
              <input id="delete_access_token" name="delete_access_token" type="hidden" value="<?php echo $access_token ?>">
              <input id="delete_resource_owner_id" name="delete_resource_owner_id" type="hidden" value="<?php echo $resource_owner_id ?>">
              <input id="country" name="country" type="hidden" value="<?php echo $country ?>">
              <input type='submit' value='DELETE' class='btn btn-primary'>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
