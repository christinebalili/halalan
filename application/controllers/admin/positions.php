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

class Positions extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index($election_id = 0)
	{
		if ($this->input->cookie('selected_election'))
		{
			$election_id = $this->input->cookie('selected_election');
		}
		$data['election_id'] = $election_id;
		$data['elections'] = $this->Election->for_dropdown();
		$data['positions'] = $this->Position->select_all_by_election_id($election_id);
		$admin['title'] = e('admin_positions_title');
		$admin['body'] = $this->load->view('admin/positions', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	public function add()
	{
		$this->_position('add');
	}

	public function edit($id)
	{
		$this->_position('edit', $id);
	}

	public function delete($id) 
	{
		if ( ! $id)
		{
			redirect('admin/positions');
		}
		$position = $this->Position->select($id);
		if ( ! $position)
		{
			redirect('admin/positions');
		}
		if ($this->Position->in_running_election($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_position_in_running_election')));
		}
		else if ($this->Position->in_use($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_delete_position_in_use')));
		}
		else
		{
			$this->Position->delete($id);
			$this->session->set_flashdata('messages', array('positive', e('admin_delete_position_success')));
		}
		redirect('admin/positions');
	}

	public function _position($case, $id = null)
	{
		if ($case == 'add')
		{
			$data['position'] = array('election_id' => '', 'position' => '', 'description' => '', 'maximum' => '', 'ordinality' => '', 'abstain' => '1', 'unit' => '0');
			$this->session->unset_userdata('position'); // so callback rules know that the action is add
		}
		else if ($case == 'edit')
		{
			if ( ! $id)
			{
				redirect('admin/positions');
			}
			$data['position'] = $this->Position->select($id);
			if ( ! $data['position'])
			{
				redirect('admin/positions');
			}
			if ($this->Position->in_running_election($id))
			{
				$this->session->set_flashdata('messages', array('negative', e('admin_position_in_running_election')));
				redirect('admin/positions');
			}
			$this->session->set_userdata('position', $data['position']); // used in callback rules
		}
		$this->form_validation->set_rules('election_id', e('admin_position_election'), 'required|callback__rule_running_election');
		$this->form_validation->set_rules('position', e('admin_position_position'), 'required|callback__rule_position_exists|callback__rule_dependencies');
		$this->form_validation->set_rules('description', e('admin_position_description'));
		$this->form_validation->set_rules('maximum', e('admin_position_maximum'), 'required|is_natural_no_zero');
		$this->form_validation->set_rules('ordinality', e('admin_position_ordinality'), 'required|is_natural_no_zero');
		$this->form_validation->set_rules('abstain', e('admin_position_abstain'));
		$this->form_validation->set_rules('unit', e('admin_position_unit'));
		if ($this->form_validation->run())
		{
			$position['election_id'] = $this->input->post('election_id', TRUE);
			$position['position'] = $this->input->post('position', TRUE);
			$position['description'] = $this->input->post('description', TRUE);
			$position['maximum'] = $this->input->post('maximum', TRUE);
			$position['ordinality'] = $this->input->post('ordinality', TRUE);
			$position['abstain'] = $this->input->post('abstain', TRUE);
			$position['unit'] = $this->input->post('unit', TRUE);
			if ($case == 'add')
			{
				$this->Position->insert($position);
				$this->session->set_flashdata('messages', array('positive', e('admin_add_position_success')));
				redirect('admin/positions/add');
			}
			else if ($case == 'edit')
			{
				$this->Position->update($position, $id);
				$this->session->set_flashdata('messages', array('positive', e('admin_edit_position_success')));
				redirect('admin/positions/edit/' . $id);
			}
		}
		$data['elections'] = $this->Election->for_dropdown();
		$data['action'] = $case;
		$admin['title'] = e('admin_' . $case . '_position_title');
		$admin['body'] = $this->load->view('admin/position', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	// a position cannot be added to a running election
	public function _rule_running_election()
	{
		if ($this->Election->is_running($this->input->post('election_id')))
		{
			$this->form_validation->set_message('_rule_running_election', e('admin_position_running_election'));
			return FALSE;
		}
		return TRUE;
	}

	// positions must have different names in an election
	public function _rule_position_exists()
	{
		$position = trim($this->input->post('position', TRUE));
		$test = $this->Position->select_by_position($position);
		if ( ! empty($test) && $test['election_id'] == $this->input->post('election_id'))
		{
			$error = FALSE;
			if ($position = $this->session->userdata('position')) // check when in edit mode
			{
				if ($test['id'] != $position['id'])
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
				$message = e('admin_position_exists') . ' (' . $test['position'] . ')';
				$this->form_validation->set_message('_rule_position_exists', $message);
				return FALSE;
			}
		}
		return TRUE;
	}

	// a position cannot change election when it already has candidates under it
	public function _rule_dependencies()
	{
		if ($position = $this->session->userdata('position')) // check when in edit mode
		{
			// don't check if no election is selected since we already have a rule for this
			if ( ! $this->input->post('election_id'))
			{
				return TRUE;
			}
			// don't check if election does not change
			if ($position['election_id'] == $this->input->post('election_id'))
			{
				return TRUE;
			}
			if ($this->Position->in_use($position['id']))
			{
				$this->form_validation->set_message('_rule_dependencies', e('admin_position_dependencies'));
				return FALSE;
			}
		}
		return TRUE;
	}

}

/* End of file positions.php */
/* Location: ./application/controllers/admin/positions.php */
