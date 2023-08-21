<?php
/**
* Author : Habib urRehman
* Email  : chaudryhabib2@gmail.com 
* Github : https://github.com/oldravian
* Website: http://redravian.com/
*/
ini_set('max_execution_time', 120);
set_time_limit(120);

include 'generate-post.php';
include 'publish-post.php';

$post = generatePost();
if(isset($post) && gettype($post) == 'array'){
    pagePost($post);
}

?>