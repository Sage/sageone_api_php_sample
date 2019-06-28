<div class="guide">
  <h3>Step by Step Guide</h3>
  <ol>
    <li>
      Sign up for a developer account <a href="https://developerselfservice.sageone.com/" target="_blank">here</a>.
    </li>
    <li>
      Create a client application. Enter <span class="pre">http://<?php echo $_SERVER['HTTP_HOST'] ?>/callback.php</span> as the callback URL.
    </li>
    <li>
      Put client ID, secret and callback URL into <span class="pre">config/client_application.yml</span>.
    </li>
    <li>Create trial user account. Click on one of the flag links at the bottom.</li>
    <li><a href="<?php echo $apiClient->authorizationEndpoint(); ?>">Authorize</a> API access.</li>
    <li>Make your first API call.</li>
    <li>Be happy!</li>
  </ol>
</div>
