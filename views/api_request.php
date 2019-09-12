
<?php
  if ($apiClient->getAccessTokenStore()) {
    $token = $apiClient->getAccessToken();
    $expiresIn = $apiClient->getExpiresAt() - time();
    $expired = $expiresIn <= 0;
    
    if ($expired) {
      $expiresMessage = "Expired <strong>" . abs($expiresIn) . "</strong> seconds ago";
    } else {
      $expiresMessage = "Expires in <strong>" . $expiresIn . "</strong> seconds";
    }
?>

<form id="request" class="request" action="api_request.php" method="post">

  <div class="field">
    <label class="field__label">Access Token</label>
    <p id="timer" class="field__help">...</p>
    <div class="field__body">
      <input class="field__input" value="<?php echo $token ?>" required readonly />
      <a class="button" href="/refresh_token.php">Refresh Token</a>
    </div>
  </div>

  <script>
    // Token expiry timer
    (function() {
      var timer = document.querySelector('#timer');
      var input = timer.closest('.field').querySelector('.field__input');
      var refresh = timer.closest('.field').querySelector('.button');
      var expires = new Date();
          expires.setSeconds(expires.getSeconds() + <?php echo $expiresIn ?>);

      function updateSeconds() {
        var now = Date.now();

        if (expires > now) {
          timer.innerHTML = 'Expires in <strong>' + Math.round((expires - now) / 1000) + '</strong> seconds';
          timer.classList.remove('is-error');
          input.classList.remove('is-invalid');
          refresh.classList.remove('is-destructive');
        }
        else {
          timer.innerHTML = 'Expired <strong>' + Math.round((now - expires) / 1000) + '</strong> seconds ago';
          timer.classList.add('is-error');
          input.classList.add('is-invalid');
          refresh.classList.add('is-destructive');
        }
      }
      
      updateSeconds();

      setInterval(updateSeconds, 1000);
    }());
  </script>

  <div class="field">
    <label class="field__label" for="http_verb">Request Method</label>
    <div class="field__body">
      <select class="field__input" id="http_verb" name="http_verb">
        <option value="get">GET</option>
        <option value="post">POST</option>
        <option value="put">PUT</option>
        <option value="delete">DELETE</option>
      </select>
    </div>
  </div>

  <div class="field">
    <label class="field__label" for="resource">Request Endpoint</label>
    <p class="field__help">
      https://api.accounting.sage.com/v3.1/<strong>&lt;Resource&gt;</strong>
    </p>
    <div class="field__body">
      <input class="field__input" list="resources" value="contacts" id="resource" name="resource" required/>
      <datalist id="resources">
        <option>contacts</option>
        <option>oranges</option>
      </datalist>
    </div>
  </div>

  <div class="field">
    <label class="field__label" for="post_data">Request Body</label>
    <p class="field__help">
      Example: {"contact": { "contact_type_ids": ["CUSTOMER"], "name": "Joe Bloggs"}}
    </p>
    <div class="field__body">
      <textarea class="field__input" id="post_data" name="post_data"></textarea>
    </div>
  </div>

  <div class="form__button">
    <button class="button">Make Request</button>
  </div>

</form>

<?php
  }
  else {
    ?>
    <a class="button is-centered" href="<?php echo $apiClient->authorizationEndpoint(); ?>">Authorize API access</a>
    <?php
  }
?>