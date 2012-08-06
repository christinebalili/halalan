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

class Candidates extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->set_module('candidate');
	}
	
	public function index()
	{
		$election_id = 0;
		$position_id = 0;
		if ($this->input->cookie('selected_election'))
		{
			$election_id = $this->input->cookie('selected_election');
		}
		if ($this->input->cookie('selected_position'))
		{
			$position_id = $this->input->cookie('selected_position');
		}
		$candidates = array();
		if ($position_id == 0)
		{
			$positions = $this->Position->select_all_by_election_id($election_id);
			foreach ($positions as $key => $value)
			{
				$tmp = $this->Candidate->select_all_by_election_id_and_position_id($election_id, $value['id']);
				if (empty($tmp))
				{
					continue;
				}
				$candidates = array_merge($candidates, $tmp);
			}
		}
		else
		{
			$candidates = $this->Candidate->select_all_by_election_id_and_position_id($election_id, $position_id);
		}
		$data['election_id'] = $election_id;
		$data['elections'] = $this->Election->for_dropdown();
		$data['position_id'] = $position_id;
		$data['positions'] = $this->Position->for_dropdown($election_id);
		$data['candidates'] = $candidates;
		$admin['title'] = e('admin_candidates_title');
		$admin['body'] = $this->load->view('admin/candidates', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	public function add()
	{
		if ($this->input->is_ajax_request())
		{
			$election_id = $this->input->post('election_id');
			$positions = $this->Position->select_all_by_election_id($election_id);
			$parties = $this->Party->select_all_by_election_id($election_id);
			echo json_encode(array($positions, $parties));
		}
		else
		{
			$this->_candidate('add');
		}
	}

	public function edit($id)
	{
		if ($this->input->is_ajax_request())
		{
			$election_id = $this->input->post('election_id');
			$positions = $this->Position->select_all_by_election_id($election_id);
			$parties = $this->Party->select_all_by_election_id($election_id);
			echo json_encode(array($positions, $parties));
		}
		else
		{
			$this->_candidate('edit', $id);
		}
	}

	public function delete($id) 
	{
		if ( ! $id)
		{
			redirect('admin/candidates');
		}
		$candidate = $this->Candidate->select($id);
		if ( ! $candidate)
		{
			redirect('admin/candidates');
		}
		if ($this->Candidate->in_running_election($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_candidate_in_running_election')));
		}
		else if ($this->Candidate->in_use($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_delete_candidate_already_has_votes')));
		}
		else
		{
			if ( ! empty($candidate['picture']))
			{
				unlink(HALALAN_UPLOAD_PATH . 'pictures/' . $candidate['picture']);
			}
			$this->Candidate->delete($id);
			$this->session->set_flashdata('messages', array('positive', e('admin_delete_candidate_success')));
		}
		redirect('admin/candidates');
	}

	public function _candidate($case, $id = null)
	{
		$election_id = 0;
		if ($case == 'add')
		{
			$data['candidate'] = array('first_name' => '', 'last_name' => '', 'alias' => '', 'description' => '', 'election_id' => '', 'position_id' => '', 'party_id' => '');
			if ($this->input->cookie('selected_election'))
			{
				$data['candidate']['election_id'] = $this->input->cookie('selected_election');
				$election_id = $this->input->cookie('selected_election');
			}
			if ($this->input->cookie('selected_position'))
			{
				$data['candidate']['position_id'] = $this->input->cookie('selected_position');
			}
			$this->session->unset_userdata('candidate'); // so callback rules know that the action is add
		}
		else if ($case == 'edit')
		{
			if ( ! $id)
			{
				redirect('admin/candidates');
			}
			$data['candidate'] = $this->Candidate->select($id);
			if ( ! $data['candidate'])
			{
				redirect('admin/candidates');
			}
			if ($this->Candidate->in_running_election($id))
			{
				$this->session->set_flashdata('messages', array('negative', e('admin_candidate_in_running_election')));
				redirect('admin/candidates');
			}
			if (empty($_POST))
			{
				$election_id = $data['candidate']['election_id'];
			}
			$this->session->set_userdata('candidate', $data['candidate']); // so callback rules know that the action is edit
		}
		// validation rules are in the config file
		if ($this->form_validation->run('_candidate'))
		{
			$candidate['election_id'] = $this->input->post('election_id', TRUE);
			$candidate['position_id'] = $this->input->post('position_id', TRUE);
			$candidate['party_id'] = $this->input->post('party_id', TRUE);
			$candidate['first_name'] = $this->input->post('first_name', TRUE);
			$candidate['last_name'] = $this->input->post('last_name', TRUE);
			$candidate['alias'] = $this->input->post('alias', TRUE);
			$candidate['description'] = $this->input->post('description', TRUE);
			if ($picture = $this->session->userdata('image_upload_data'))
			{
				$candidate['picture'] = $picture;
				$this->session->unset_userdata('image_upload_data');
			}
			if ($case == 'add')
			{
				$this->Candidate->insert($candidate);
				$this->session->set_flashdata('messages', array('positive', e('admin_add_candidate_success')));
				redirect('admin/candidates/add');
			}
			else if ($case == 'edit')
			{
				$this->Candidate->update($candidate, $id);
				$this->session->set_flashdata('messages', array('positive', e('admin_edit_candidate_success')));
				redirect('admin/candidates/edit/' . $id);
			}
		}
		else
		{
			if ($picture = $this->session->userdata('image_upload_data'))
			{
				unlink(HALALAN_UPLOAD_PATH . 'pictures/' . $picture);
				$this->session->unset_userdata('image_upload_data');
			}
		}
		if ($this->input->post('election_id'))
		{
			$election_id = $this->input->post('election_id');
		}
		$data['elections'] = $this->Election->for_dropdown();
		$data['positions'] = $this->Position->for_dropdown($election_id);
		$data['parties'] = $this->Party->for_dropdown($election_id);
		$data['action'] = $case;
		$admin['title'] = e('admin_' . $case . '_candidate_title');
		$admin['body'] = $this->load->view('admin/candidate', $data, TRUE);
		$this->load->view('admin', $admin);
	}

}

/* End of file candidates.php */
/* Location: ./application/controllers/admin/candidates.php */
