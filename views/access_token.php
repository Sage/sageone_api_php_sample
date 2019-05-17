<?php

$access_token_store = new AccessTokenStore();
if ($access_token_store->load()) {

  $expiresIn = $access_token_store->getExpiresAt() - time();
  if ($expiresIn > 0) {
    $expiresMessage = "expires in {$expiresIn} seconds";
  } else
  {
    $expiresMessage = "has expired " . abs($expiresIn) . " seconds ago";
  }
?>
<div class="access-token">
  <h3>Access Token</h3>
  <p>Access Token <?php echo $expiresMessage; ?>. <a href="/refresh_token.php">Refresh Token</a></p>
</div>
<?php
}
?>
