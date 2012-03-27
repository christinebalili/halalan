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

class Blocks extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->set_module('block');
	}
	
	public function index($election_id = 0)
	{
		if ($this->input->cookie('selected_election'))
		{
			$election_id = $this->input->cookie('selected_election');
		}
		$data['election_id'] = $election_id;
		$data['elections'] = $this->Election->for_dropdown();
		$data['blocks'] = $this->Block->select_all_by_election_id($election_id);
		$admin['title'] = e('admin_blocks_title');
		$admin['body'] = $this->load->view('admin/blocks', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	public function add()
	{
		if ($this->input->is_ajax_request())
		{
			$election_ids = json_decode($this->input->post('election_ids', TRUE));
			echo json_encode($this->_fill_positions($election_ids));
		}
		else
		{
			$this->_block('add');
		}
	}

	public function edit($id)
	{
		if ($this->input->is_ajax_request())
		{
			$election_ids = json_decode($this->input->post('election_ids', TRUE));
			echo json_encode($this->_fill_positions($election_ids));
		}
		else
		{
			$this->_block('edit', $id);
		}
	}

	public function delete($id) 
	{
		if ( ! $id)
		{
			redirect('admin/blocks');
		}
		$block = $this->Block->select($id);
		if ( ! $block)
		{
			redirect('admin/blocks');
		}
		if ($this->Block->in_running_election($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_block_in_running_election')));
		}
		else if ($this->Block->in_use($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_delete_block_in_use')));
		}
		else
		{
			$this->Block->delete($id);
			$this->session->set_flashdata('messages', array('positive', e('admin_delete_block_success')));
		}
		redirect('admin/blocks');
	}

	public function _block($case, $id = null)
	{
		$chosen_elections = array();
		$chosen_positions = array();
		if ($case == 'add')
		{
			$data['block'] = array('block' => '');
			$this->session->unset_userdata('block'); // so callback rules know that the action is add
		}
		else if ($case == 'edit')
		{
			if ( ! $id)
			{
				redirect('admin/blocks');
			}
			$data['block'] = $this->Block->select($id);
			if ( ! $data['block'])
			{
				redirect('admin/blocks');
			}
			if ($this->Block->in_running_election($id))
			{
				$this->session->set_flashdata('messages', array('negative', e('admin_block_in_running_election')));
				redirect('admin/blocks');
			}
			if (empty($_POST))
			{
				$tmp = $this->Block_Election_Position->select_all_by_block_id($id);
				foreach ($tmp as $t)
				{
					$chosen_elections[] = $t['election_id'];
					$chosen_positions[] = $t['election_id'] . '|' . $t['position_id'];
				}
				$chosen_elections = array_unique($chosen_elections);
			}
			$this->session->set_userdata('block', $data['block']); // so callback rules know that the action is edit
		}
		$this->form_validation->set_rules('block', e('admin_block_block'), 'required|callback__rule_is_existing[blocks.block]|callback__rule_dependencies');
		$this->form_validation->set_rules('chosen_elections', e('admin_block_chosen_elections'), 'required|callback__rule_running_election');
		if ($this->form_validation->run())
		{
			$block['block'] = $this->input->post('block', TRUE);
			// chosen elections are also in the ids of the positions
			//$block['chosen_elections'] = $this->input->post('chosen_elections', TRUE);
			$general_positions = $this->input->post('general_positions', TRUE);
			$chosen_positions = $this->input->post('chosen_positions', TRUE);
			if ( ! $chosen_positions)
			{
				// if no chosen positions, its value is FALSE so convert to array
				$chosen_positions = array();
			}
			$extra = array();
			foreach (array_merge($general_positions, $chosen_positions) as $p)
			{
				list($election_id, $position_id) = explode('|', $p);
				$extra[] = array('election_id' => $election_id, 'position_id' => $position_id);
			}
			$block['extra'] = $extra;
			if ($case == 'add')
			{
				$this->Block->insert($block);
				$this->session->set_flashdata('messages', array('positive', e('admin_add_block_success')));
				redirect('admin/blocks/add');
			}
			else if ($case == 'edit')
			{
				$this->Block->update($block, $id);
				$this->session->set_flashdata('messages', array('positive', e('admin_edit_block_success')));
				redirect('admin/blocks/edit/' . $id);
			}
		}
		if ($this->input->post('chosen_elections'))
		{
			$chosen_elections = $this->input->post('chosen_elections');
		}
		if ($this->input->post('chosen_positions'))
		{
			$chosen_positions = $this->input->post('chosen_positions');
		}
		$data['elections'] = $this->Election->select_all();
		$data['possible_elections'] = array();
		$data['chosen_elections'] = array();
		foreach ($data['elections'] as $e)
		{
			if (in_array($e['id'], $chosen_elections))
			{
				$data['chosen_elections'][$e['id']] = '(' . $e['id'] . ') ' . $e['election'];
			}
			else
			{
				$data['possible_elections'][$e['id']] = '(' . $e['id'] . ') ' . $e['election'];
			}
		}
		$fill = $this->_fill_positions($chosen_elections);
		$data['general_positions'] = array();
		foreach ($fill[0] as $f)
		{
			$data['general_positions'][$f['value']] = $f['text'];
		}
		$data['possible_positions'] = array();
		foreach ($fill[1] as $f)
		{
			$data['possible_positions'][$f['value']] = $f['text'];
		}
		$data['chosen_positions'] = array();
		foreach ($data['possible_positions'] as $key => $value)
		{
			if (in_array($key, $chosen_positions))
			{
				$data['chosen_positions'][$key] = $value;
				unset($data['possible_positions'][$key]);
			}
		}
		$data['action'] = $case;
		$admin['title'] = e('admin_' . $case . '_block_title');
		$admin['body'] = $this->load->view('admin/block', $data, TRUE);
		$this->load->view('admin', $admin);
	}

}

/* End of file blocks.php */
/* Location: ./application/controllers/admin/blocks.php */
