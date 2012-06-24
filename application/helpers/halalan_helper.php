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
 * Return formatted validation errors or custom messages
 *
 * @access	public
 * @param	string
 * @param	array
 * @return	string
 */
function display_messages($validation, $custom)
{
	$return = '';
	// negatives take precedent
	// but we are sure that only one of the params has values
	if ( ! empty($validation))
	{
		$return .= '<div class="alert alert-error"><ul>';
		$return .= $validation;
		$return .= '</ul></div>';
	}
	else if ( ! empty($custom))
	{
		if ($custom[0] == 'positive')
		{
			$return .= '<div class="alert alert-success"><ul>';
		}
		else
		{
			$return .= '<div class="alert alert-error"><ul>';
		}
		unset($custom[0]);
		$return .= '<li>' . implode('</li><li>', $custom) . '</li>';
		$return .= '</ul></div>';
	}
	return $return;
}


/* End of file halalan_helper.php */
/* Location: ./application/heleprs/halalan_helper.php */