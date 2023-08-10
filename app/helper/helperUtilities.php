<?php
define('ERROR','error');


function sendToast($msg, $status = 'success')
{
    return [
        'toast' => true, 
        'status' => $status, 
        'message' => $msg
    ];
}
