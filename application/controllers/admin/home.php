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

class Home extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$data = array();
		$admin['title'] = e('admin_home_title');
		$admin['body'] = $this->load->view('admin/home', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	public function do_regenerate()
	{
		$error = array();
		$voter = array();
		if ($this->input->post('username'))
		{
			if ( ! $voter = $this->Boter->select_by_username($this->input->post('username')))
			{
				$error[] = e('admin_regenerate_not_exists');
			}
		}
		else
		{
			if ($this->config->item('halalan_password_pin_generation') == 'web')
			{
				$error[] = e('admin_regenerate_no_username');
			}
			else if ($this->config->item('halalan_password_pin_generation') == 'email')
			{
				$error[] = e('admin_regenerate_no_email');
			}
		}
		if ($this->config->item('halalan_password_pin_generation') == 'email')
		{
			if ( ! $this->form_validation->valid_email($this->input->post('username')))
			{
				$error[] = e('admin_regenerate_invalid_email');
			}
		}
		if (empty($error))
		{
			$password = random_string($this->config->item('halalan_password_pin_characters'), $this->config->item('halalan_password_length'));
			$voter['password'] = sha1($password);
			if ($this->config->item('halalan_pin') && $this->input->post('pin'))
			{
				$pin = random_string($this->config->item('halalan_password_pin_characters'), $this->config->item('halalan_password_length'));
				$voter['pin'] = sha1($pin);
			}
			if ($this->input->post('login'))
			{
				$voter['login'] = NULL;
			}
			$this->Boter->update($voter, $voter['id']);
			$success = array();
			$success[] = e('admin_regenerate_success');
			if ($this->config->item('halalan_password_pin_generation') == 'web')
			{
				$success[] = 'Username: '. $voter['username'];
				$success[] = 'Password: '. $password;
				if ($this->config->item('halalan_pin') && $this->input->post('pin'))
				{
					$success[] = 'PIN: '. $pin;
				}
			}
			else if ($this->config->item('halalan_password_pin_generation') == 'email')
			{
				$this->email->from($this->session->userdata('email'), $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'));
				$this->email->to($voter['username']);
				$this->email->subject('Halalan Login Credentials');
				$message = "Hello $voter[first_name] $voter[last_name],\n\nThe following are your login credentials:\nEmail: $voter[username]\n";
				$message .= "Password: $password\n";
				if ($this->config->item('halalan_pin') && $this->input->post('pin'))
				{
					$message .= "PIN: $pin\n";
				}
				$message .= "\n";
				$message .= ($this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'));
				$message .= "\n";
				$message .= 'Halalan Administrator';
				$this->email->message($message);
				$this->email->send();
				//echo $this->email->print_debugger();
				$success[] = e('admin_regenerate_email_success');
			}
			$this->session->set_flashdata('messages', array_merge(array('positive'), $success));
		}
		else
		{
			$this->session->set_flashdata('messages', array_merge(array('negative'), $error));
		}
		redirect('admin/home');
	}

}

/* End of file home.php */
/* Location: ./application/controllers/admin/home.php */
