<div class="error">
  <h3>There was a problem loading client_application.yml</h3>
  <p>Before you can start, you need to prepare a config file, which contains information
    about your registered client application.</p>
  <p>Follow these steps to get this sample application running:</p>
  <ul>
    <li>Make a copy of the file <span class="pre">config/client_application.template.yml</span> and
      name it <span class="pre">config/client_application.yml</span>.</li>
    <li>Go to the Sage Developer Self Service at
      <a href="https://developerselfservice.sageone.com/">https://developerselfservice.sageone.com/</a>
      and sign up or sign in.</li>
    <li>Create a new application (app) and copy the values for Client ID and Client Secret
      into the fields in <span class="pre">client_config.yml</span>.</li>
    <li>Depending on the way you run the sample app (Docker environment, local Apache,
      etc.) adjust the hostname/port of the callback URL below. Add the same callback URL
      to your client application in the Sage Developer Self Service.</li>
  </ul>
</div>
