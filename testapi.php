<?php


/**
* How to test go to repository folder run php -S localhost:8080 
*In another terminal run php -f testapi.php
*
*/



$country="GB";
$accessToken="ACCESS_TOKEN_GOES_HERE";
$getendpoint="accounts/v3/contacts";



$apiData = http_build_query(
    array(
        'get_endpoint'=>$getendpoint,
        'country' => 'GB',
        'get_access_token' => $accessToken,
        'get_resource_owner_id'=>'OWNER_ID_GOES_HERE'
    )
);

$options = array('http' =>
                  array(
                      'method'  => 'GET',
                      'content' => $apiData
                  )
);

$context  = stream_context_create($options);

$result = file_get_contents("http://localhost:8080/sageone_response.php?".$apiData, false, $context);


echo $result;