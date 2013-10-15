<?php
$e = new Exception(lang('exception_error_1845'), 1845);
$jsonresponse = new Jsonresponse();
$jsonresponse->set_message($message);
echo  $jsonresponse->show_error($e);
exit();
?>