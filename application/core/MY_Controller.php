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

class MY_Controller extends CI_controller {

	public function __construct()
	{
		parent::__construct();
		if ($this->uri->segment(1) == 'admin') // admin side
		{
			if ($this->session->userdata('type') != 'admin')
			{
				$this->session->set_flashdata('messages', array('negative', e('common_unauthorized')));
				redirect('gate/admin');
			}
		}
	}

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
