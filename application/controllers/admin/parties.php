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

class Parties extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->set_module('party');
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
		$data['parties'] = $this->Party->select_all_by_election_id($election_id);
		$admin['title'] = e('admin_parties_title');
		$admin['body'] = $this->load->view('admin/parties', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	public function add()
	{
		$this->_party('add');
	}

	public function edit($id)
	{
		$this->_party('edit', $id);
	}

	public function delete($id) 
	{
		if ( ! $id)
		{
			redirect('admin/parties');
		}
		$party = $this->Party->select($id);
		if ( ! $party)
		{
			redirect('admin/parties');
		}
		if ($this->Party->in_running_election($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_party_in_running_election')));
		}
		else if ($this->Party->in_use($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_delete_party_in_use')));
		}
		else
		{
			if ( ! empty($party['logo']))
			{
				unlink(HALALAN_UPLOAD_PATH . 'logos/' . $party['logo']);
			}
			$this->Party->delete($id);
			$this->session->set_flashdata('messages', array('positive', e('admin_delete_party_success')));
		}
		redirect('admin/parties');
	}

	public function _party($case, $id = null)
	{
		if ($case == 'add')
		{
			$data['party'] = array('election_id' => '', 'party' => '', 'alias' => '', 'description' => '');
			if ($this->input->cookie('selected_election'))
			{
				$data['party']['election_id'] = $this->input->cookie('selected_election');
			}
			$this->session->unset_userdata('party'); // so callback rules know that the action is add
		}
		else if ($case == 'edit')
		{
			if ( ! $id)
			{
				redirect('admin/parties');
			}
			$data['party'] = $this->Party->select($id);
			if ( ! $data['party'])
			{
				redirect('admin/parties');
			}
			if ($this->Party->in_running_election($id))
			{
				$this->session->set_flashdata('messages', array('negative', e('admin_party_in_running_election')));
				redirect('admin/parties');			
			}
			$this->session->set_userdata('party', $data['party']); // so callback rules know that the action is edit
		}
		// validation rules are in the config file
		if ($this->form_validation->run('_party'))
		{
			$party['election_id'] = $this->input->post('election_id', TRUE);
			$party['party'] = $this->input->post('party', TRUE);
			$party['alias'] = $this->input->post('alias', TRUE);
			$party['description'] = $this->input->post('description', TRUE);
			if ($logo = $this->session->userdata('image_upload_data'))
			{
				$party['logo'] = $logo;
				$this->session->unset_userdata('image_upload_data');
			}
			if ($case == 'add')
			{
				$this->Party->insert($party);
				$this->session->set_flashdata('messages', array('positive', e('admin_add_party_success')));
				redirect('admin/parties/add');
			}
			else if ($case == 'edit')
			{
				$this->Party->update($party, $id);
				$this->session->set_flashdata('messages', array('positive', e('admin_edit_party_success')));
				redirect('admin/parties/edit/' . $id);
			}
		}
		else
		{
			if ($logo = $this->session->userdata('image_upload_data'))
			{
				unlink(HALALAN_UPLOAD_PATH . 'logos/' . $logo);
				$this->session->unset_userdata('image_upload_data');
			}
		}
		$data['elections'] = $this->Election->for_dropdown();
		$data['action'] = $case;
		$admin['title'] = e('admin_' . $case . '_party_title');
		$admin['body'] = $this->load->view('admin/party', $data, TRUE);
		$this->load->view('admin', $admin);
	}

}

/* End of file parties.php */
/* Location: ./application/controllers/admin/parties.php */
