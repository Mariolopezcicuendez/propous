<?php
$error = new StdClass();
$error->code = $status_code; 
$error->message = $message;

$json_result = new StdClass();
$json_result->status = $error->code;
$json_result->error = $error;

header("Content-Type: text/json");
echo json_encode($json_result);
?>