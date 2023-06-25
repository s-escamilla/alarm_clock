<?php
// Read the JSON file contents into a string
// Read the JSON file contents into a string
$jsonString = file_get_contents('data.json');

// Decode the JSON data into a PHP associative array
$data = json_decode($jsonString, true);


// Retrieve the auth token
$accessToken = $data['Auth_token']["token"];
$expirationTime =  $data['Auth_token']["expires"];


// Get the current timestamp
$currentTimestamp = time();

// Check if the expiration time has passed
if ($expirationTime < $currentTimestamp) {
    // echo 'The Auth_token has expired.';
    $url = 'https://www.strava.com/api/v3/oauth/token';
    $client_id = $data["Refresh_params"]["id"];
    $client_secret = $data["Refresh_params"]["secret"];
    $grant_type = $data["Refresh_params"]["grant_type"];
    $refresh_token = $data["Refresh_token"]["token"];

    $postData = array(
      'client_id' => $client_id,
      'client_secret' => $client_secret,
      'grant_type' => $grant_type,
      'refresh_token' => $refresh_token
  );
    $curl_refresh = curl_init();

    // Set cURL options
        curl_setopt($curl_refresh, CURLOPT_URL, $url);
        curl_setopt($curl_refresh, CURLOPT_POST, true);
        curl_setopt($curl_refresh, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl_refresh, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request
    $response = curl_exec($curl_refresh);

    // Close cURL
    curl_close($curl_refresh);

    // Check if the request was successful
    if ($response === false) {
        echo 'cURL error: ' . curl_error($curl_refresh);
    } else {
        // Handle the response
        $responseData = json_decode($response, true);

        // Update the data array with the refreshed token information
        $data['Auth_token']['token'] = $responseData['access_token'];
        $data['Auth_token']['expires'] = $responseData['expires_at'];
        $data['Refresh_token']['token'] = $responseData['refresh_token'];
        // Encode the updated data as JSON
        $updatedJsonData = json_encode($data, JSON_PRETTY_PRINT);

        // Write the updated JSON data back to the file
        file_put_contents('data.json', $updatedJsonData);

        // Output the refreshed token response
        var_dump($responseData);
    }
} else {
    echo 'The Auth_token is still valid.';

// Get the current time

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://www.strava.com/api/v3/athlete/activities?',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => array(
    
    'Authorization: Bearer ' . $accessToken
  )
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo 'cURL Error: ' . $err;
} else {
  $response = json_decode($response, true);
  $startDateTime = $response[0]['start_date_local'];
  $startTimestamp = strtotime($startDateTime);
  

    // Check if the start_timestamp is within the last 12 hours
    $twelveHoursAgo = strtotime('-12 hours');
    if ($startTimestamp >= $twelveHoursAgo && $startTimestamp <= $currentTimestamp) {
        echo 'The start_timestamp is within the last 12 hours.';
    } else {
        echo 'The start_timestamp is not within the last 12 hours.';
        $output = shell_exec('/bin/python3 /home/sescamilla/Documents/Projects/strava/alarm_clock/venmo.py');

    }

  
}}

?>
