<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Language Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/language_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Lang
 *
 * Fetches a language variable and optionally outputs a form label
 *
 * @access	public
 * @param	string	the language line
 * @param	string	the id of the form element
 * @return	string
 */
if ( ! function_exists('lang'))
{
	function lang($line, $id = '')
	{
		$CI =& get_instance();
		$line = $CI->lang->line($line);

		if (($id !== null) && (is_array($id)))
		{
			for ($i = 0; $i < count($id); $i++) 
			{
				$line = preg_replace('/%s/', $id[$i], $line, 1);
			}
		}
		elseif ($id != '')
		{
			$line = '<label for="'.$id.'">'.$line."</label>";
		}

		return $line;
	}
}

// ------------------------------------------------------------------------

/**
 * GetActLang
 *
 * Return actual language tag
 *
 * @return	string
 */
if ( ! function_exists('getActLang'))
{
	function getActLang()
	{
		$lang_class = new MY_Lang();
		$lang = $lang_class->lang();
		return $lang;
	}
}

// ------------------------------------------------------------------------

/**
 * GetActLang
 *
 * Return actual language tag
 *
 * @return	string
 */
if ( ! function_exists('get_all_translations'))
{
	function get_all_translations()
	{
		$CI =& get_instance();
		$lang = $CI->lang->get_all_translations();
		return $lang;
	}
}

// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */