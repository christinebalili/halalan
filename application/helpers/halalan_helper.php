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

/**
 * Return form_input with custom HTML
 *
 * @access	public
 * @param	string
 * @return	string
 */
function form_input_html($name, $value, $extra, $help, $lang_key, $error = TRUE)
{
	$CI =& get_instance();
	$CI->load->helper('form');
	$label = e($lang_key);
	if (empty($label))
	{
		$CI->load->helper('inflector');
		$label = humanize($name);
	}
	$label = form_label($label . ':', $name, array('class' => 'control-label'));
	$element = form_input($name, set_value($name, $value), 'id="' . $name . '" ' . $extra);
	$class = '';
	if ($error)
	{
		$help = form_error($name) ? form_error($name, ' ', ' ') : $help;
		$class = form_error($name) ? ' error' : '';
	}
	return _form_element_html_template($label, $element, $help, $class);
}

/**
 * Return submit button with custom HTML
 *
 * @access	public
 * @param	string
 * @return	string
 */
function form_submit_html($value, $cancel = TRUE)
{
	$html = '<div class="form-actions"><button type="submit" class="btn btn-primary">';
	$html .= $value;
	$html .= '</button>';
	if ($cancel)
	{
		$html .= "\n" . '<button type="button" class="btn">Cancel</button>';
	}
	$html .= '</div>';
	return $html;
}

/**
 * Return form element with custom HTML template
 *
 * @access	private
 * @param	array
 * @return	string
 */
function _form_element_html_template($label, $element, $help, $class = '')
{
$html = <<<EOD
<div class="control-group{$class}">
	{$label}
	<div class="controls">
		{$element}
		<span class="help-inline">{$help}</span>
	</div>
</div>
EOD;
return $html;
}


/* End of file halalan_helper.php */
/* Location: ./application/heleprs/halalan_helper.php */
