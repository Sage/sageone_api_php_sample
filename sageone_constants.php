<?php

$client_id = 'YOUR_CLIENT_ID';
$client_secret = 'YOUR_CLIENT_SECRET';
$signing_secret = 'YOUR_SIGNING_SECRET';
$apim_subscription_key = 'YOUR APIM SUBSCRIPTION_KEY';
$callback_url = 'http://localhost:8080/callback.php';
$scope = 'full_access';
$endpoints = [
  'auth' => 'https://www.sageone.com/oauth2/auth/central',
  'token' => [
    'CA' => 'https://mysageone.ca.sageone.com/oauth2/token',
    'DE' => 'https://oauth.eu.sageone.com/token',
    'ES' => 'https://oauth.eu.sageone.com/token',
    'FR' => 'https://oauth.eu.sageone.com/token',
    'GB' => 'https://app.sageone.com/oauth2/token',
    'IE' => 'https://app.sageone.com/oauth2/token',
    'US' => 'https://mysageone.na.sageone.com/oauth2/token'
  ],
  'base' => [
    'CA' => 'https://api.columbus.sage.com/ca/sageone/',
    'DE' => 'https://api.columbus.sage.com/de/sageone/',
    'ES' => 'https://api.columbus.sage.com/es/sageone/',
    'FR' => 'https://api.columbus.sage.com/fr/sageone/',
    'GB' => 'https://api.columbus.sage.com/uki/sageone/',
    'IE' => 'https://api.columbus.sage.com/uki/sageone/',
    'US' => 'https://api.columbus.sage.com/us/sageone/'
  ]
];

?>
