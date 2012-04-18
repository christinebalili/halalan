<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Copyright (C) 2012 University of the Philippines Linux Users' Group
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

class Positions extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->set_module('position');
	}
	
	public function index()
	{
		$election_id = 0;
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
			if ($this->input->cookie('selected_election'))
			{
				$data['position']['election_id'] = $this->input->cookie('selected_election');
			}
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
		// validation rules are in the config file
		if ($this->form_validation->run('_position'))
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

}

/* End of file positions.php */
/* Location: ./application/controllers/admin/positions.php */
