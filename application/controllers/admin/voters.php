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

class Voters extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->set_module('voter');
	}
	
	public function index($offset = 0)
	{
		$block_id = 0;
		if ($this->input->cookie('selected_block'))
		{
			$block_id = $this->input->cookie('selected_block');
		}
		$voters = $this->Boter->select_all_by_block_id($block_id);
		$config['base_url'] = site_url('admin/voters/index');
		$config['total_rows'] = count($voters);
		$config['per_page'] = HALALAN_PER_PAGE;
		$config['uri_segment'] = 4;
		$config['num_links'] = 5;
		$config['first_link'] = img('public/images/go-first.png');
		$config['last_link'] = img('public/images/go-last.png');
		$config['prev_link'] = img('public/images/go-previous.png');
		$config['next_link'] = img('public/images/go-next.png');
		$this->pagination->initialize($config); 
		$data['links'] = $this->pagination->create_links();
		$limit = $config['per_page'];
		$data['offset'] = $offset;
		$data['limit'] = $limit;
		$data['total_rows'] = $config['total_rows'];
		$data['voters'] = $this->Boter->select_all_by_block_id_for_pagination($block_id, $limit, $offset);
		$data['block_id'] = $block_id;
		$data['blocks'] = $this->Block->for_dropdown();
		$admin['title'] = e('admin_voters_title');
		$admin['body'] = $this->load->view('admin/voters', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	public function add()
	{
		$this->_voter('add');
	}

	public function edit($id)
	{
		$this->_voter('edit', $id);
	}

	public function delete($id) 
	{
		if ( ! $id)
		{
			redirect('admin/voters');
		}
		$voter = $this->Boter->select($id);
		if ( ! $voter)
		{
			redirect('admin/voters');
		}
		if ($this->Boter->in_running_election($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_voter_in_running_election')));
		}
		else if ($this->Boter->in_use($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_delete_voter_already_voted')));
		}
		else
		{
			$this->Boter->delete($id);
			$this->session->set_flashdata('messages', array('positive', e('admin_delete_voter_success')));
		}
		redirect('admin/voters');
	}

	public function _voter($case, $id = null)
	{
		$chosen_elections = array();
		$chosen_positions = array();
		if ($case == 'add')
		{
			$data['voter'] = array('username' => '', 'first_name' => '', 'last_name' => '', 'block_id' => '');
			if ($this->input->cookie('selected_block'))
			{
				$data['voter']['block_id'] = $this->input->cookie('selected_block');
			}
			$this->session->unset_userdata('voter'); // so callback rules know that the action is add
		}
		else if ($case == 'edit')
		{
			if ( ! $id)
			{
				redirect('admin/voters');
			}
			$data['voter'] = $this->Boter->select($id);
			if ( ! $data['voter'])
			{
				redirect('admin/voters');
			}
			if ($this->Boter->in_running_election($id))
			{
				$this->session->set_flashdata('messages', array('negative', e('admin_voter_in_running_election')));
				redirect('admin/voters');
			}
			$this->session->set_userdata('voter', $data['voter']); // so callback rules know that the action is edit
		}
		// validation rules are in the config file
		if ($this->form_validation->run('_voter_' . $this->config->item('halalan_password_pin_generation')))
		{
			$password = '';
			$pin = '';
			$voter['username'] = $this->input->post('username', TRUE);
			$voter['first_name'] = $this->input->post('first_name', TRUE);
			$voter['last_name'] = $this->input->post('last_name', TRUE);
			$voter['block_id'] = $this->input->post('block_id', TRUE);
			if ($case == 'add' || $this->input->post('password'))
			{
				$password = random_string($this->config->item('halalan_password_pin_characters'), $this->config->item('halalan_password_length'));
				$voter['password'] = sha1($password);
			}
			if ($this->config->item('halalan_pin'))
			{
				if ($case == 'add' || $this->input->post('pin'))
				{
					$pin = random_string($this->config->item('halalan_password_pin_characters'), $this->config->item('halalan_pin_length'));
					$voter['pin'] = sha1($pin);
				}
			}
			$messages[] = 'positive';
			if ($case == 'add')
			{
				$this->Boter->insert($voter);
				$messages[] = e('admin_add_voter_success');
			}
			else if ($case == 'edit')
			{
				$this->Boter->update($voter, $id);
				$messages[] = e('admin_edit_voter_success');
			}
			if ($this->config->item('halalan_password_pin_generation') == 'web')
			{
				if ( ! empty($password))
				{
					$messages[] = 'Password: '. $password;
				}
				if ( ! empty($pin))
				{
					$messages[] = 'PIN: '. $pin;
				}
			}
			else if ($this->config->item('halalan_password_pin_generation') == 'email')
			{
				$messages[] = 'Username: '. $voter['username'];
				if ( ! empty($password) OR ! empty($pin))
				{
					$this->_send_email($voter, $password, $pin);
					$messages[] = e('admin_voter_email_success');
				}
			}
			if ($case == 'add')
			{
				$this->session->set_flashdata('messages', $messages);
				redirect('admin/voters/add');
			}
			else if ($case == 'edit')
			{
				$this->session->set_flashdata('messages', $messages);
				redirect('admin/voters/edit/' . $id);
			}
		}
		$data['blocks'] = $this->Block->select_all();
		$data['action'] = $case;
		$admin['title'] = e('admin_' . $case . '_voter_title');
		$admin['body'] = $this->load->view('admin/voter', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	public function import()
	{
		// validation rules are in the config file
		if ($this->form_validation->run('import'))
		{
			$voter['password'] = '';
			$voter['block_id'] = $this->input->post('block_id', TRUE);
			$upload_data = $this->session->userdata('csv_upload_data');
			$csv = array();
			if ($handle = fopen($upload_data['full_path'], 'r'))
			{
				while ($data = fgetcsv($handle, 1000))
				{
					$csv[] = $data;
				}
				fclose($handle);
			}
			unset($csv[0]); // remove header
			$count = 0;
			foreach ($csv as $value)
			{
				$voter['username'] = $value[0];
				$voter['last_name'] = $value[1];
				$voter['first_name'] = $value[2];
				if ($voter['username'] && $voter['last_name'] && $voter['first_name'] && ! $this->Boter->select_by_username($voter['username']))
				{
					if ($this->config->item('halalan_password_pin_generation') == 'web')
					{
						$this->Boter->insert($voter);
						$count++;
					}
					else if ($this->config->item('halalan_password_pin_generation') == 'email')
					{
						if ($this->form_validation->valid_email($voter['username']))
						{
							$this->Boter->insert($voter);
							$count++;
						}
					}
				}
			}
			if ($count == 1)
			{
				$success[] = $count . e('admin_import_success_singular');
			}
			else
			{
				$success[] = $count . e('admin_import_success_plural');
			}
			$reminder = e('admin_import_reminder');
			if ($this->config->item('halalan_pin'))
			{
				$reminder = trim($reminder, '.'); // remove period
				$reminder .= e('admin_import_reminder_too');
			}
			$success[] = $reminder;
			unlink($upload_data['full_path']);
			$this->session->unset_userdata('csv_upload_data');
			$this->session->set_flashdata('messages', array_merge(array('positive'), $success));
			redirect('admin/voters/import');
		}
		if ($upload_data = $this->session->userdata('csv_upload_data'))
		{
			// delete csv file when upload is successful but other fields have problems
			unlink($upload_data['full_path']);
			$this->session->unset_userdata('csv_upload_data');
		}
		$data['blocks'] = $this->Block->select_all();
		$admin['title'] = e('admin_import_title');
		$admin['body'] = $this->load->view('admin/import', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	public function export()
	{
		// validation rules are in the config file
		if ($this->form_validation->run('export'))
		{
			$header = '';
			if ($this->config->item('halalan_password_pin_generation') == 'web')
			{
				$header = 'Username';
			}
			else if ($this->config->item('halalan_password_pin_generation') == 'email')
			{
				$header = 'Email';
			}
			$header .= ',Last Name,First Name';
			if ($this->input->post('status'))
			{
				$header .= ',Voted';
			}
			$data[] = $header;
			$voters = $this->Boter->select_all_by_block_id($this->input->post('block_id', TRUE));
			foreach ($voters as $voter)
			{
				$row = $voter['username'] . ',' . $voter['last_name'] . ',' . $voter['first_name'];
				if ($this->input->post('status'))
				{
					$voted = $this->Voted->select_all_by_voter_id($voter['id']);
					$tmp = array();
					foreach ($voted as $v)
					{
						$election = $this->Election->select($v['election_id']);
						$tmp[] = $election['election'];
					}
					$row .= ',' . implode(' | ', $tmp);
				}
				$data[] = $row;
			}
			$data = implode("\r\n", $data);
			force_download('voters.csv', $data);
		}
		$data['blocks'] = $this->Block->for_dropdown();
		$admin['title'] = e('admin_export_title');
		$admin['body'] = $this->load->view('admin/export', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	public function generate()
	{
		// validation rules are in the config file
		if ($this->form_validation->run('generate'))
		{
			$block_id = $this->input->post('block_id', TRUE);
			$voters = $this->Boter->select_all_by_block_id($block_id);
			if ($this->config->item('halalan_password_pin_generation') == 'web')
			{
				$header = '#,Last Name,First Name,Username,Password';
				if ($this->config->item('halalan_pin'))
				{
					$header .= ',PIN';
				}
				$data[] = $header;
				$count = 1;
				foreach ($voters as $voter)
				{
					$boter = array();
					$row = $count . ',' . $voter['last_name'] . ',' . $voter['first_name'] . ',' . $voter['username'];
					$password = random_string($this->config->item('halalan_password_pin_characters'), $this->config->item('halalan_password_length'));
					$row .= ',' . $password;
					$boter['password'] = sha1($password);
					if ($this->config->item('halalan_pin'))
					{
						$pin = random_string($this->config->item('halalan_password_pin_characters'), $this->config->item('halalan_pin_length'));
						$row .= ',' . $pin;
						$boter['pin'] = sha1($pin);
					}
					$this->Boter->update($boter, $voter['id']);
					$data[] = $row;
					$count = $count + 1;
				}
				$data = implode("\r\n", $data);
				force_download('block-' . $block_id . '.csv', $data);
			}
			else if ($this->config->item('halalan_password_pin_generation') == 'email')
			{
				foreach ($voters as $voter)
				{
					$password = '';
					$pin = '';
					$password = random_string($this->config->item('halalan_password_pin_characters'), $this->config->item('halalan_password_length'));
					$boter['password'] = sha1($password);
					if ($this->config->item('halalan_pin'))
					{
						$pin = random_string($this->config->item('halalan_password_pin_characters'), $this->config->item('halalan_pin_length'));
						$boter['pin'] = sha1($pin);
					}
					$this->Boter->update($boter, $voter['id']);
					$this->_send_email($voter, $password, $pin);
				}
			}
		}
		$data['blocks'] = $this->Block->for_dropdown();
		$admin['title'] = $this->config->item('halalan_pin') ? e('admin_generate_password_pin_title') : e('admin_generate_password_title');
		$admin['body'] = $this->load->view('admin/generate', $data, TRUE);
		$this->load->view('admin', $admin);
	}

}

/* End of file voters.php */
/* Location: ./application/controllers/admin/voters.php */
