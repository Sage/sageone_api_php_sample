<?php

if (isset($_SESSION['api_response'])) {

  $response = unserialize($_SESSION['api_response']);

?>
<div class="api-response ">
  <h3>Last API Response</h3>

  <div class="http-status">HTTP Status Code <?php echo $response->getStatus(); ?></div>
  <div class="json"><?php echo json_encode($response->getJSON(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></div>

  <!-- div class="request-duration"><?php echo $response->getDuration(); ?> seconds</div -->
</div>
<?php
}
?>
