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

class Gate extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if (in_array($this->uri->segment(2), array('admin', 'voter')))
		{
			if ($this->session->userdata('type') == 'admin')
			{
				redirect('admin/home');
			}
			else if ($this->session->userdata('type') == 'voter')
			{
				redirect('voter/vote');
			}
		}
		
	}

	public function index()
	{
		$gate['active'] = 'home';
		$gate['title'] = 'Home';
		$gate['body'] = $this->load->view('gate/index', '', TRUE);
		$this->load->view('gate', $gate);
	}

	public function voter()
	{
		$this->_no_cache();
		if ( ! empty($_POST))
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			if ( ! $username OR ! $password)
			{
				$messages = array('negative', e('gate_common_login_failure'));
				$this->session->set_flashdata('messages', $messages);
				redirect('gate/voter');
			}
			if ($voter = $this->Boter->authenticate($username, sha1($password)))
			{
				if (strtotime($voter['login']) > strtotime($voter['logout']))
				{
					$messages = array('negative', e('gate_voter_currently_logged_in'));
					$this->session->set_flashdata('messages', $messages);
					redirect('gate/voter');
				}
				else
				{
					$this->Boter->update(array('login' => date("Y-m-d H:i:s"), 'ip_address' => ip2long($this->input->ip_address())), $voter['id']);
					$this->session->set_userdata('id', $voter['id']);
					$this->session->set_userdata('type', 'voter');
					$this->session->set_userdata('username', $voter['username']);
					$this->session->set_userdata('first_name', $voter['first_name']);
					$this->session->set_userdata('last_name', $voter['last_name']);
					$this->session->set_userdata('block_id', $voter['block_id']);
					redirect('voter/vote');
				}
			}
			else
			{
				$messages = array('negative', e('gate_common_login_failure'));
				$this->session->set_flashdata('messages', $messages);
				redirect('gate/voter');
			}
		}
		$gate['active'] = 'voter';
		$gate['title'] = e('gate_voter_title');
		$gate['body'] = $this->load->view('gate/voter', '', TRUE);
		$this->load->view('gate', $gate);
	}
	
	public function admin()
	{
		if ( ! empty($_POST))
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			if ( ! $username OR ! $password)
			{
				$messages = array('negative', e('gate_common_login_failure'));
				$this->session->set_flashdata('messages', $messages);
				redirect('gate/admin');
			}
			if ($admin = $this->Abmin->authenticate($username, sha1($password)))
			{
				$this->session->set_userdata('id', $admin['id']);
				$this->session->set_userdata('type', 'admin');
				$this->session->set_userdata('username', $admin['username']);
				$this->session->set_userdata('first_name', $admin['first_name']);
				$this->session->set_userdata('last_name', $admin['last_name']);
				$this->session->set_userdata('email', $admin['email']);
				redirect('admin/home');
			}
			else
			{
				$messages = array('negative', e('gate_common_login_failure'));
				$this->session->set_flashdata('messages', $messages);
				redirect('gate/admin');
			}
		}
		$gate['active'] = 'admin';
		$gate['title'] = e('gate_admin_title');
		$gate['body'] = $this->load->view('gate/admin', '', TRUE);
		$this->load->view('gate', $gate);
	}

	public function logout()
	{
		if ($this->session->userdata('type') == 'admin')
		{
			$gate = 'admin';
		}
		else if ($this->session->userdata('type') == 'voter')
		{
			// voter has not yet voted
			$this->Boter->update(array('logout' => date("Y-m-d H:i:s")), $this->session->userdata('id'));
			$gate = 'voter';
		}
		else
		{
			// voter has already voted
			$gate = 'voter';
		}
		// delete cookies
		$this->input->set_cookie('halalan_abstain'); // used in abstain alert
		$this->input->set_cookie('selected_election'); // used in remembering selected election
		$this->input->set_cookie('selected_position'); // used in remembering selected position
		$this->input->set_cookie('selected_block'); // used in remembering selected block
		$this->session->sess_destroy();
		redirect('gate/' . $gate);
	}

	public function results()
	{
		$selected = $this->input->post('elections', TRUE);
		$all_elections = $this->Election->select_all_with_results();
		$elections = $this->Election->select_all_by_ids($selected);
		foreach ($elections as $key1 => $election)
		{
			$positions = $this->Position->select_all_by_election_id($election['id']);
			foreach ($positions as $key2 => $position)
			{
				$candidates = array();
				$votes = $this->Vote->count_all_by_election_id_and_position_id($election['id'], $position['id']);
				foreach ($votes as $vote)
				{
					$candidate = $this->Candidate->select($vote['candidate_id']);
					$candidate['votes'] = $vote['votes'];
					$candidate['party'] = $this->Party->select($candidate['party_id']);
					$candidate['breakdown'] = $this->Vote->breakdown($election['id'], $candidate['id']);
					$candidates[] = $candidate;
				}
				$positions[$key2]['candidates'] = $candidates;
				$positions[$key2]['abstains'] = $this->Abstain->count_all_by_election_id_and_position_id($election['id'], $position['id']);
				$positions[$key2]['breakdown'] = $this->Abstain->breakdown($election['id'], $position['id']);
			}
			$elections[$key1]['positions'] = $positions;
		}
		// $this->input->post returns FALSE so make it an array to avoid in_array errors
		if ($selected == FALSE)
		{
			$selected = array();
		}
		$data['selected'] = $selected;
		$data['all_elections'] = $all_elections;
		$data['elections'] = $elections;
		$data['settings'] = $this->config->item('halalan');
		$gate['login'] = 'results';
		$gate['title'] = e('gate_results_title');
		$gate['body'] = $this->load->view('gate/results', $data, TRUE);
		$this->load->view('gate', $gate);
	}

	public function statistics()
	{
		$this->load->model('Statistics');
		$selected = $this->input->post('elections', TRUE);
		$all_elections = $this->Election->select_all_with_results();
		$elections = $this->Election->select_all_by_ids($selected);
		foreach ($elections as $key => $election)
		{
			$elections[$key]['voter_count'] = $this->Statistics->count_all_voters($election['id']);
			$elections[$key]['voter_breakdown'] = $this->Statistics->breakdown_all_voters($election['id']);
			$elections[$key]['voted_count'] = $this->Statistics->count_all_voted($election['id']);
			$elections[$key]['voted_breakdown'] = $this->Statistics->breakdown_all_voted($election['id']);

			$elections[$key]['lt_one'] = $this->Statistics->count_all_by_duration($election['id'], '00:00:00', '00:01:00');
			$elections[$key]['lt_one_breakdown'] = $this->Statistics->breakdown_all_by_duration($election['id'], '00:00:00', '00:01:00');
			$elections[$key]['lt_two_gte_one'] = $this->Statistics->count_all_by_duration($election['id'], '00:01:00', '00:02:00');
			$elections[$key]['lt_two_gte_one_breakdown'] = $this->Statistics->breakdown_all_by_duration($election['id'], '00:01:00', '00:02:00');
			$elections[$key]['lt_three_gte_two'] = $this->Statistics->count_all_by_duration($election['id'], '00:02:00', '00:03:00');
			$elections[$key]['lt_three_gte_two_breakdown'] = $this->Statistics->breakdown_all_by_duration($election['id'], '00:02:00', '00:03:00');
			$elections[$key]['gt_three'] = $this->Statistics->count_all_by_duration($election['id'], '00:03:00', FALSE);
			$elections[$key]['gt_three_breakdown'] = $this->Statistics->breakdown_all_by_duration($election['id'], '00:03:00', FALSE);
		}
		// $this->input->post returns FALSE so make it an array to avoid in_array errors
		if ($selected == FALSE)
		{
			$selected = array();
		}
		$data['selected'] = $selected;
		$data['all_elections'] = $all_elections;
		$data['elections'] = $elections;
		$gate['login'] = 'statistics';
		$gate['title'] = e('gate_statistics_title');
		$gate['body'] = $this->load->view('gate/statistics', $data, TRUE);
		$this->load->view('gate', $gate);
	}

	public function ballots($block_id = 0)
	{
		$array = array();
		$elections = array();
		$chosen = $this->Block_Election_Position->select_all_by_block_id($block_id);
		foreach ($chosen as $c)
		{
			$array[$c['election_id']][] = $c['position_id'];
		}
		if ( ! empty($array))
		{
			$elections = $this->Election->select_all_by_ids(array_keys($array));
			foreach ($elections as $key1 => $election)
			{
				$positions = $this->Position->select_all_by_ids($array[$election['id']]);
				foreach ($positions as $key2 => $position)
				{
					$candidates = $this->Candidate->select_all_by_election_id_and_position_id($election['id'], $position['id']);
					foreach ($candidates as $key3 => $candidate)
					{
						$candidates[$key3]['party'] = $this->Party->select($candidate['party_id']);
					}
					$positions[$key2]['candidates'] = $candidates;
				}
				$elections[$key1]['positions'] = $positions;
			}
		}
		$blocks = $this->Block->select_all();
		$tmp = array();
		foreach ($blocks as $block)
		{
			$tmp[$block['id']] = $block['block'];
		}
		$blocks = $tmp;
		$data['block_id'] = $block_id;
		$data['blocks'] = $blocks;
		$data['elections'] = $elections;
		$gate['login'] = 'ballots';
		$gate['title'] = e('gate_ballots_title');
		$gate['body'] = $this->load->view('gate/ballots', $data, TRUE);
		$this->load->view('gate', $gate);
	}

	public function _no_cache()
	{
		// from http://stackoverflow.com/questions/49547/making-sure-a-web-page-is-not-cached-across-all-browsers
		header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
		header('Pragma: no-cache'); // HTTP 1.0.
		header('Expires: 0'); // Proxies.
	}

}

/* End of file gate.php */
/* Location: ./application/controllers/gate.php */