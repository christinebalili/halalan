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
			$pin = '';
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
				if ( ! empty($pin))
				{
					$success[] = 'PIN: '. $pin;
				}
			}
			else if ($this->config->item('halalan_password_pin_generation') == 'email')
			{
				$this->_send_email($voter, $password, $pin);
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
