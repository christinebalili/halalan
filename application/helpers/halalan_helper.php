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
 * CodeIgniter Halalan Helper
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		UP Linux Users' Group
 * @link		http://uplug.org
 */

// ------------------------------------------------------------------------

/**
 * Return line from language file
 *
 * @access	public
 * @param	string
 * @return	string
 */	
function e($line)
{
	$CI =& get_instance();
	return $CI->lang->line('halalan_' . $line);
}

/**
 * Return formatted custom messages
 *
 * @access	public
 * @param	array
 * @return	string
 */
function display_messages($custom)
{
	if (empty($custom))
	{
		return '';
	}
	$return = '';
	if ($custom[0] == 'positive')
	{
		$return .= '<div class="alert alert-success">';
	}
	else
	{
		$return .= '<div class="alert alert-error">';
	}
	unset($custom[0]);
	$return .= implode('<br />', $custom) . '</div>';
	return $return;
}


/* End of file halalan_helper.php */
/* Location: ./application/heleprs/halalan_helper.php */
