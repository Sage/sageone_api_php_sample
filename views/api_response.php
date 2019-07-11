<?php
  if ($apiClient->getAccessTokenStore() && isset($_SESSION['api_response'])) {
      $response = unserialize($_SESSION['api_response']);
?>

<div id="response" class="response">
  <div class="field">
    <label class="field__label">Response Status</label>
    <p class="field__help">
      Returned in <strong><?php echo $response->getDuration(); ?></strong> seconds
    </p>
    <div class="field__body">
      <input class="field__input" readonly value="<?php echo $response->getStatus(); ?>" />
    </div>
  </div>

  <div class="field">
    <label class="field__label">Response Body</label>
    <div class="field__body">
      <textarea class="field__input response__body" readonly><?php
        echo json_encode($response->getJSON(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
      ?></textarea>
    </div>
  </div>
</div>

<?php
  }
?>
