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

	private $module;

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

	public function set_module($module)
	{
		$this->module = $module;
	}

	public function get_module()
	{
		return $this->module;
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
	public function _rule_running_election()
	{
		if ($this->module == 'block')
		{
			if ($this->Election->are_running($this->input->post('chosen_elections')))
			{
				$this->form_validation->set_message('_rule_running_election', e('admin_block_running_election'));
				return FALSE;
			}
			// additional check since an election may have no positions yet
			if ( ! $this->input->post('general_positions') && ! $this->input->post('chosen_positions'))
			{
				$this->form_validation->set_message('_rule_running_election', e('admin_block_no_positions'));
				return FALSE;
			}
		}
		else if ($this->module == 'voter')
		{
			if ($this->Block->in_running_election($this->input->post('block_id')))
			{
				$this->form_validation->set_message('_rule_running_election', e('admin_voter_running_election'));
				return FALSE;
			}
		}
		else
		{
			if ($this->Election->is_running($this->input->post('election_id')))
			{
				$this->form_validation->set_message('_rule_running_election', e('admin_' . $this->module . '_running_election'));
				return FALSE;
			}
		}
		return TRUE;
	}

	public function _rule_is_existing($str, $table_fields)
	{
		// modified is_unique rule
		list($table, $fields) = explode('.', $table_fields);
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
			if ($data = $this->session->userdata($this->module)) // check when in edit mode
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
				if ($this->module == 'candidate')
				{
					$value = $test[$fields[0]] . ', ' . $test[$fields[1]];
					if ( ! empty($test[$fields[2]]))
					{
						$value .= ' "' . $test[$fields[2]] . '"';
					}
				}
				$message = e('admin_' . $this->module . '_exists') . ' (' . $value . ')';
				$this->form_validation->set_message('_rule_is_existing', $message);
				return FALSE;
			}
		}
		return TRUE;
	}

	public function _rule_dependencies()
	{
		if ($test = $this->session->userdata($this->module)) // check when in edit mode
		{
			if ($this->module == 'block')
			{
				// don't check if no elections or positions are selected since we already have a rule for them
				if ( ! $this->input->post('chosen_elections'))
				{
					return TRUE;
				}
				// don't check if elections and positions do not change
				$chosen_elections = array();
				$chosen_positions = array();
				$tmp = $this->Block_Election_Position->select_all_by_block_id($test['id']);
				foreach ($tmp as $t)
				{
					$chosen_elections[] = $t['election_id'];
					$chosen_positions[] = $t['election_id'] . '|' . $t['position_id'];
				}
				$chosen_elections = array_unique($chosen_elections);
				$fill = $this->_fill_positions($chosen_elections);
				$general_positions = array();
				foreach ($fill[0] as $f)
				{
					$general_positions[] = $f['value'];
				}
				$tmp = FALSE; // not array() since $this->input->post returns FALSE when empty
				foreach ($chosen_positions as $c)
				{
					// remove from $chosen_positions the general positions
					if ( ! in_array($c, $general_positions))
					{
						$tmp[] = $c;
					}
				}
				$chosen_positions = $tmp;
				if ($chosen_elections == $this->input->post('chosen_elections') && $general_positions == $this->input->post('general_positions') && $chosen_positions == $this->input->post('chosen_positions'))
				{
					return TRUE;
				}
			}
			else if ($this->module == 'voter')
			{
				// don't check if no block is selected since we already have a rule for this
				if ( ! $this->input->post('block_id'))
				{
					return TRUE;
				}
				// don't check if block does not change
				if ($test['block_id'] == $this->input->post('block_id'))
				{
					return TRUE;
				}
			}
			else if ($this->module == 'candidate')
			{
				// don't check if no election or position is selected since we already have a rule for them
				if ( ! $this->input->post('election_id') OR ! $this->input->post('position_id'))
				{
					return TRUE;
				}
				// don't check if election and position do not change
				if ($test['election_id'] == $this->input->post('election_id') && $test['position_id'] == $this->input->post('position_id'))
				{
					return TRUE;
				}
			}
			else
			{
				// don't check if no election is selected since we already have a rule for this
				if ( ! $this->input->post('election_id'))
				{
					return TRUE;
				}
				// don't check if election does not change
				if ($test['election_id'] == $this->input->post('election_id'))
				{
					return TRUE;
				}
			}
			$model = ucfirst($this->module);
			if ($model == 'Voter')
			{
				$model = 'Boter';
			}
			if ($this->$model->in_use($test['id']))
			{
				$this->form_validation->set_message('_rule_dependencies', e('admin_' . $this->module . '_dependencies'));
				return FALSE;
			}
		}
		return TRUE;
	}

	// used by blocks
	public function _fill_positions($election_ids)
	{
		$general = array();
		$specific = array();
		foreach ($election_ids as $election_id)
		{
			$positions = $this->Position->select_all_by_election_id($election_id);
			foreach ($positions as $position)
			{
				$value = $election_id . '|' . $position['id'];
				$text = $position['position'] . ' (' . $election_id . ')';
				if ($position['unit'])
				{
					$specific[] = array('value' => $value, 'text' => $text);
				}
				else
				{
					$general[] = array('value' => $value, 'text' => $text);
				}
			}
		}
		return array($general, $specific);
	}

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
