<?php

if ($apiClient->getAccessTokenStore()) {

  $expiresIn = $apiClient->getExpiresAt() - time();
  if ($expiresIn > 0) {

?>
<div class="api-request">
  <h3>Make API Request</h3>

  <form action="api_request.php" method="post" class="endpoint_form">
    <div class="api-request-verb-url">
      <label for="http_verb">HTTP Verb</label>
      <select name="http_verb">
        <option value="get">GET</option>
        <option value="post">POST</option>
        <option value="put">PUT</option>
        <option value="delete">DELETE</option>
      </select>
      <span><?php echo SageoneClient::BASE_ENDPOINT; ?></span>
      <label for="resource">Resource</label>
      <input name="resource" type="text" value="contacts" class="form-control" required="true">
    </div>
    <label for="post_data">Post data</label>
    <textarea class="form-control" name="post_data"></textarea>
    <p>Example: {"contact": { "contact_type_ids": ["CUSTOMER"], "name": "Joe Bloggs"}}</p>
    <input type='submit' class='btn btn-primary'>
  </form>
</div>
<?php
  }
}
?>
