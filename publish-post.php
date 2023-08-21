<?php
/**
* Author : Habib urRehman
* Email  : chaudryhabib2@gmail.com 
* Github : https://github.com/oldravian
* Website: http://redravian.com/
*/

//turn on php error handling        
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;

function pagePost($data){
    $message = $data['message'];
    $link = $data['link'];

    $env = parse_ini_file('.env');
    $client = new Client();

    try{
        $qry = [    
            'message' => $message,
            'link' => $link,
            'access_token' => $env['long_lived_page_access_token']
        ];
    
        //get a long-lived User access token
        $response = $client->request('POST', "https://graph.facebook.com/" . $env['facebook_page_id'] . "/feed", [
            'query' => $qry
        ]);
    
        $body = $response->getBody();
        $data = json_decode($body->getContents());
        echo "Posted Successfully!";
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

?>