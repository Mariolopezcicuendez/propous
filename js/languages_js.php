<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

echo "
<script type='text/javascript'>

  var langs = new Array();
";

$lang_map = get_all_translations();

foreach ($lang_map as $key => $value) 
{
  echo "  langs['{$key}'] = '{$value}';\n";
}

echo "  
</script>";

/* End of file languages_js.php */
/* Location: ./js/languages_js.php */