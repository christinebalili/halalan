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

class Block extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function insert($block)
	{
		if (isset($block['extra']))
		{
			$extra = $block['extra'];
			unset($block['extra']);
		}
		$this->db->insert('blocks', $block);
		if ( ! empty($extra))
		{
			$block_id = $this->db->insert_id();
			foreach ($extra as $e)
			{
				$election_id = $e['election_id'];
				$position_id = $e['position_id'];
				$this->db->insert('blocks_elections_positions', compact('block_id', 'election_id', 'position_id'));
			}
		}
		return TRUE;
	}

	public function update($block, $id)
	{
		if (isset($block['extra']))
		{
			$extra = $block['extra'];
			unset($block['extra']);
			$this->db->where('block_id', $id);
			$this->db->delete('blocks_elections_positions');
		}
		$this->db->update('blocks', $block, array('id' => $id));
		if ( ! empty($extra))
		{
			$block_id = $id;
			foreach ($extra as $e)
			{
				$election_id = $e['election_id'];
				$position_id = $e['position_id'];
				$this->db->insert('blocks_elections_positions', compact('block_id', 'election_id', 'position_id'));
			}
		}
		return TRUE;
	}

	public function delete($id)
	{
		$this->db->where('block_id', $id);
		$this->db->delete('blocks_elections_positions');
		$this->db->where('id', $id);
		return $this->db->delete('blocks');
	}

	public function select($id)
	{
		$this->db->from('blocks');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function select_all()
	{
		$this->db->from('blocks');
		$this->db->order_by('block', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function select_all_by_election_id($election_id)
	{
		$this->db->distinct();
		$this->db->select('blocks.*');
		$this->db->from('blocks');
		$this->db->join('blocks_elections_positions', 'blocks.id = blocks_elections_positions.block_id');
		$this->db->where('election_id', $election_id);
		$this->db->order_by('block', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function in_use($block_id)
	{
		$this->db->from('voters');
		$this->db->where('block_id', $block_id);
		return $this->db->count_all_results() > 0 ? TRUE : FALSE;
	}

	public function in_running_election($block_id)
	{
		$this->db->from('blocks_elections_positions');
		$this->db->where('block_id', $block_id);
		$this->db->where('election_id IN (SELECT id FROM elections WHERE status = 1)');
		return $this->db->count_all_results() > 0 ? TRUE : FALSE;
	}

	public function for_dropdown()
	{
		$this->db->from('blocks');
		$this->db->order_by('block', 'ASC');
		$query = $this->db->get();
		$tmp = $query->result_array();
		$blocks = array();
		foreach ($tmp as $t)
		{
			$blocks[$t['id']] = $t['block'];
		}
		return $blocks;
	}

}

/* End of file block.php */
/* Location: ./application/models/block.php */
