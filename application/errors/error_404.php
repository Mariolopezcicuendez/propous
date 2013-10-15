<?php
$e = new Exception(lang('exception_error_1844'), 1844);
$jsonresponse = new Jsonresponse();
$jsonresponse->set_message($message);
echo  $jsonresponse->show_error($e);
exit();
?>