<?php
$e = new Exception(lang('exception_error_1850'), 1850);
$jsonresponse = new Jsonresponse();
$jsonresponse->set_message($message);
echo  $jsonresponse->show_error($e);
exit();
?>