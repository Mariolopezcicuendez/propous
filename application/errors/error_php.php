<?php
$e = new Exception(lang('exception_error_1846'), 1846);
$jsonresponse = new Jsonresponse();
$jsonresponse->set_file($filepath);
$jsonresponse->set_line($line);
$jsonresponse->set_message($message);
echo  $jsonresponse->show_error($e);
exit();
?>