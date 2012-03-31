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

class Candidates extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->set_module('candidate');
	}
	
	public function index($election_id = 0, $position_id = 0)
	{
		if ($this->input->cookie('selected_election'))
		{
			$election_id = $this->input->cookie('selected_election');
		}
		$positions = $this->Position->select_all_by_election_id($election_id);
		foreach ($positions as $key => $value)
		{
			$positions[$key]['candidates'] = $this->Candidate->select_all_by_election_id_and_position_id($election_id, $value['id']);
			if ($position_id > 0 && $value['id'] == $position_id)
			{
				$tmp = $positions[$key];
				// clear data since only one position will be displayed
				$positions = array();
				$positions[] = $tmp; 
				break;
			}
		}
		$data['election_id'] = $election_id;
		$data['elections'] = $this->Election->for_dropdown();
		$data['position_id'] = $position_id;
		$data['pos'] = $this->Position->for_dropdown($election_id);
		$data['positions'] = $positions;
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
			$candidate['first_name'] = $this->input->post('first_name', TRUE);
			$candidate['last_name'] = $this->input->post('last_name', TRUE);
			$candidate['alias'] = $this->input->post('alias', TRUE);
			$candidate['description'] = $this->input->post('description', TRUE);
			$candidate['election_id'] = $this->input->post('election_id', TRUE);
			$candidate['position_id'] = $this->input->post('position_id', TRUE);
			$candidate['party_id'] = $this->input->post('party_id', TRUE);
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
		$data['positions'] = array();
		$data['parties'] = array();
		if ($election_id > 0)
		{
			$data['positions'] = $this->Position->select_all_by_election_id($election_id);
			$data['parties'] = $this->Party->select_all_by_election_id($election_id);
		}
		$data['action'] = $case;
		$admin['title'] = e('admin_' . $case . '_candidate_title');
		$admin['body'] = $this->load->view('admin/candidate', $data, TRUE);
		$this->load->view('admin', $admin);
	}

}

/* End of file candidates.php */
/* Location: ./application/controllers/admin/candidates.php */
