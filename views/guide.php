<div id="guide" class="guide">
  <h1 class="title">Let's get started</h1>

  <ol class="steps">
    <li class="step">
      <h2 class="step__title">
        <a href="https://developerselfservice.sageone.com/" target="_blank">Sign up for a Sage developer account</a>
      </h2>
    </li>
    <li class="step">
      <h2 class="step__title">
        <a href="" target="_blank">Create a client application</a>
      </h2>
      <div class="step__body">
        Enter the following as the callback URL:<br />
        <code>http://<?php echo $_SERVER['HTTP_HOST'] ?>/callback.php</code>
      </div>
    </li>
    <li class="step">
      <h2 class="step__title">
        Copy the Client Id and Client Secret into:
      </h2>
      <div class="step__body">
        <code>config/client_application.yml</code>
      </div>
    </li>
    <li class="step">
      <h2 class="step__title">
        <a href="https://developer.sage.com/api/accounting/guides/faq/" target="_blank">Sign up for a free product trial</a>
      </h2>
    </li>
    <li class="step">
      <h2 class="step__title">
        <a href="<?php echo $apiClient->authorizationEndpoint(); ?>">Authorize API access</a>
      </h2>
    </li>
    <li class="step">
      <h2 class="step__title">
        Make your first API call
      </h2>
    </li>
  </ol>
</div>
<div class="main">
