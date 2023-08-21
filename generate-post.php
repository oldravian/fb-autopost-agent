<?php
/**
* Author : Habib urRehman
* Email  : chaudryhabib2@gmail.com 
* Github : https://github.com/oldravian
* Website: http://redravian.com/
*/

/**
 * Select random rss feed from env and use openai to generate a post using feed item title 
 */

//turn on php error handling        
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;

function generatePost(){
    $client = new Client();
    $env = parse_ini_file('.env');
    $feeds = explode(', ', $env['rss_feeds']);
    $feed_url = $feeds[array_rand($feeds)];

    try{
        $response = $client->get($feed_url);
        $rss = $response->getBody();
        $xml = simplexml_load_string($rss);
        $title=null;
        $link=null;
        foreach ($xml->channel->item as $item) {
            $title = (string) $item->title;
            $link = (string) $item->link;
            break;
        }

        $prompt = "Based on the following blog title, please create a captivating Facebook page post with relevant tags. Post length should be less than 300 characters. Strictly follow the output format I want and don't mention any links and content headings.
        Title: $title
        Output Format I want: 
        '{Facebook post detail} {new line} {4 space separated relevant hashtags}'";


        $client = OpenAI::client($env['openai_api_key']);
        try{
            $result = $client->completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $prompt,
                'max_tokens' => 400,
            ]);
            $text =  $result['choices'][0]['text'];
            
            //added new line before first #
            $text = preg_replace('/#/', "\n#", $text, 1);
            return ['message'=>$text, 'link'=>$link];
        }
        catch(\Exception $e){
            echo $e->getMessage();
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
}
?>