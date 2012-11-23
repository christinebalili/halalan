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

class Install extends CI_Controller {

	public function index()
	{
		$this->load->database(); // This will also check if the provided DB settings are correct or not
		if (strlen($this->config->item('encryption_key')) != 32)
		{
			show_error('<p>It looks like $config[\'encryption_key\'] is not set.  Please set it at line 227 of application/config/config.php with a 32-character random string.</p><p>See user_guide/libraries/encryption.html for more details.</p>');
		}
		if ($this->db->table_exists('admins')) // Maybe we should check all the tables?
		{
			show_error('<p>It looks like Halalan is already installed.  Please remove application/controllers/install.php to continue.</p>');
		}

		$this->load->helper(array('halalan', 'url'));
		$this->load->library('form_validation');

		$data['password'] = '';
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('name', 'Name', 'required');
		if ($this->form_validation->run())
		{
			$this->load->helper('string');
			$this->load->library('migration');
			$this->migration->current();
			$admin['username'] = $this->input->post('username');
			$password = random_string('alnum', mt_rand(HALALAN_PASSWORD_MINIMUM_LENGTH, HALALAN_PASSWORD_MAXIMUM_LENGTH));
			$admin['password'] = $password;
			$admin['email'] = $this->input->post('email');
			$admin['name'] = $this->input->post('name');
			$admin['type'] = 'master';
			$this->db->insert('admins', $admin);
			$data['password'] = $password;
		}

		$this->load->view('install', $data);
	}

}

/* End of file install.php */
/* Location: ./application/controllers/install.php */