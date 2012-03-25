<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Copyright (C) 2006-2012 University of the Philippines Linux Users' Group
 *
 * This file is part of Halalan.
 *
 * Halalan is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Halalan is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Halalan.  If not, see <http://www.gnu.org/licenses/>.
 */

class MY_Controller extends CI_controller {

	public function __construct()
	{
		parent::__construct();
		if ($this->uri->segment(1) == 'admin') // admin side
		{
			if ($this->session->userdata('type') != 'admin')
			{
				$this->session->set_flashdata('messages', array('negative', e('common_unauthorized')));
				redirect('gate/admin');
			}
		}
	}

	// used by admin to send email
	public function _send_email($voter, $password, $pin)
	{
		$email = $this->session->userdata('email');
		$admin = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
		$data['voter'] = $voter;
		$data['password'] = $password;
		$data['pin'] = $pin;
		$data['admin'] = $admin;
		$message = $this->load->view('admin/_email', $data, TRUE);

		$this->email->from($email, $admin);
		$this->email->to($voter['username']);
		$this->email->subject('Halalan Login Credentials');
		$this->email->message($message);
		$this->email->send();
		//echo $this->email->print_debugger();
	}

	// additional form validation rules
	public function _rule_is_existing($str, $module_table_fields)
	{
		// modified is_unique rule
		list($module, $table, $fields) = explode('.', $module_table_fields);
		$fields = explode(',', $fields);
		$where = array();
		foreach ($fields as $field)
		{
			$where[$field] = $this->input->post($field, TRUE);
		}
		$query = $this->db->limit(1)->get_where($table, $where);
		$test = $query->row_array();
		if ( ! empty($test))
		{
			$error = FALSE;
			if ($data = $this->session->userdata($module)) // check when in edit mode
			{
				if ($test['id'] != $data['id'])
				{
					$error = TRUE;
				}
			}
			else
			{
				$error = TRUE;
			}
			if ($error)
			{
				$value = $test[$fields[0]];
				if ($module == 'candidate')
				{
					$value = $test[$fields[0]] . ', ' . $test[$fields[1]];
					if ( ! empty($test[$fields[2]]))
					{
						$value .= ' "' . $test[$fields[2]] . '"';
					}
				}
				$message = e('admin_' . $module . '_exists') . ' (' . $value . ')';
				$this->form_validation->set_message('_rule_is_existing', $message);
				return FALSE;
			}
		}
		return TRUE;
	}

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
