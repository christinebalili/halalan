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

class Elections extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->set_module('election');
	}
	
	public function index()
	{
		$data['elections'] = $this->Election->select_all();
		$admin['title'] = e('admin_elections_title');
		$admin['body'] = $this->load->view('admin/elections', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	public function add()
	{
		$this->_election('add');
	}

	public function edit($id)
	{
		$this->_election('edit', $id);
	}

	public function delete($id) 
	{
		if ( ! $id)
		{
			redirect('admin/elections');
		}
		$election = $this->Election->select($id);
		if ( ! $election)
		{
			redirect('admin/elections');
		}
		if ($election['status'])
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_delete_election_running')));
		}
		else if ($this->Election->in_use($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_delete_election_in_use')));
		}
		else
		{
			$this->Election->delete($id);
			$this->session->set_flashdata('messages', array('positive', e('admin_delete_election_success')));
		}
		redirect('admin/elections');
	}

	public function options($case, $id)
	{
		if ($case == 'status' || $case == 'results')
		{
			$election = $this->Election->select($id);
			if ($election)
			{
				$data = array();
				if ($case == 'status')
				{
					$data['status'] = ! $election['status'];
				}
				else
				{
					$data['results'] = ! $election['results'];
				}
				$this->Election->update($data, $id);
				$this->session->set_flashdata('messages', array('positive', e('admin_options_election_success')));
			}
		}
		redirect('admin/elections');
	}

	public function _election($case, $id = null)
	{
		if ($case == 'add')
		{
			$data['election'] = array('election' => '', 'description' => '');
			$this->session->unset_userdata('election'); // so callback rules know that the action is add
		}
		else if ($case == 'edit')
		{
			if ( ! $id)
			{
				redirect('admin/elections');
			}
			$data['election'] = $this->Election->select($id);
			if ( ! $data['election'])
			{
				redirect('admin/elections');
			}
			if ($data['election']['status'])
			{
				$this->session->set_flashdata('messages', array('negative', e('admin_edit_election_running')));
				redirect('admin/elections');
			}
			$this->session->set_userdata('election', $data['election']); // so callback rules know that the action is edit
		}
		// validation rules are in the config file
		if ($this->form_validation->run('_election'))
		{
			$election['election'] = $this->input->post('election', TRUE);
			$election['description'] = $this->input->post('description', TRUE);
			if ($case == 'add')
			{
				$this->Election->insert($election);
				$this->session->set_flashdata('messages', array('positive', e('admin_add_election_success')));
				redirect('admin/elections/add');
			}
			else if ($case == 'edit')
			{
				$this->Election->update($election, $id);
				$this->session->set_flashdata('messages', array('positive', e('admin_edit_election_success')));
				redirect('admin/elections/edit/' . $id);
			}
		}
		$data['action'] = $case;
		$admin['title'] = e('admin_' . $case . '_election_title');
		$admin['body'] = $this->load->view('admin/election', $data, TRUE);
		$this->load->view('admin', $admin);
	}

}

/* End of file elections.php */
/* Location: ./application/controllers/admin/elections.php */
