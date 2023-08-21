<?php
/**
* Author : Habib urRehman
* Email  : chaudryhabib2@gmail.com 
* Github : https://github.com/oldravian
* Website: http://redravian.com/
*/

/**
 * docs: https://developers.facebook.com/docs/pages/access-tokens/
*/

// If you used a short-lived User access token, the Page access token is valid for 1 hour.
// If you used a long-lived User access token, the Page access token has no expiration date
// we need long lived page access token, that's why we use short lived user access token to generate long lived user access token so that we can finnaly generate long lived page access token 
// Out flow: short-lived-user-access-token -> long-lived-user-access-token -> long-lived-page-access-token

//turn on php error handling        
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
$env = parse_ini_file('.env');
use GuzzleHttp\Client;


// Use one of the following methods to get a short-lived User access token:
// The Graph Explorer tool
// The Facebook Login Dialog

//I'm using first methods (The Graph Explorer tool)
//access the tool here: https://developers.facebook.com/tools/explorer and put the generated token in $short_lived_user_access_token variable

$short_lived_user_access_token=$env['short_lived_user_access_token'];
$fcebook_app_id=$env['fcebook_app_id'];
$facebook_app_secret=$env['facebook_app_secret'];


try{
    $client = new Client();

    $qry = [
        'grant_type' => 'fb_exchange_token',
        'client_id' => $fcebook_app_id,
        'client_secret' => $facebook_app_secret,
        'fb_exchange_token' => $short_lived_user_access_token
    ];

    //get a long-lived User access token
    $response = $client->request('GET', "https://graph.facebook.com/oauth/access_token", [
        'query' => $qry
    ]);

    $body = $response->getBody();
    $data = json_decode($body->getContents());

    try{
        $qry = [
            'fields' => 'name,access_token',
            'access_token' => $data->access_token
        ];
    
        //get a long-lived User access token
        $response = $client->request('GET', "https://graph.facebook.com/" . $env['facebook_page_id'], [
            'query' => $qry
        ]);
    
        $body = $response->getBody();
        $data = json_decode($body->getContents());
        var_dump($data);
        
    } 
    catch (\GuzzleHttp\Exception\RequestException $e){
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            var_dump($response->getReasonPhrase()); // Response message;
            var_dump(json_decode((string) $response->getBody())); // Body as the decoded JSON;
        }
        else{
            var_dump($e->getMessage());
        }
    }
    
} 
catch (\GuzzleHttp\Exception\RequestException $e){
    if ($e->hasResponse()) {
        $response = $e->getResponse();
        var_dump($response->getReasonPhrase()); // Response message;
        var_dump(json_decode((string) $response->getBody())); // Body as the decoded JSON;
    }
    else{
        var_dump($e->getMessage());
    }
}



?>