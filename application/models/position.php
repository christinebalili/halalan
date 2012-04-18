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

class Position extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function insert($position)
	{
		return $this->db->insert('positions', $position);
	}

	public function update($position, $id)
	{
		return $this->db->update('positions', $position, array('id' => $id));
	}

	public function delete($id)
	{
		$this->db->where('position_id', $id);
		$this->db->delete('blocks_elections_positions');
		$this->db->where('id', $id);
		return $this->db->delete('positions');
	}

	public function select($id)
	{
		$this->db->from('positions');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function select_all()
	{
		$this->db->from('positions');
		$this->db->order_by('ordinality', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function select_all_by_ids($ids)
	{
		$this->db->from('positions');
		$this->db->where_in('id', $ids);
		$this->db->order_by('ordinality', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function select_all_by_election_id($election_id)
	{
		$this->db->from('positions');
		$this->db->where('election_id', $election_id);
		$this->db->order_by('ordinality', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function in_use($position_id)
	{
		$this->db->from('candidates');
		$this->db->where('position_id', $position_id);
		$has_candidates = $this->db->count_all_results() > 0 ? TRUE : FALSE;
		$this->db->from('blocks_elections_positions');
		$this->db->where('position_id', $position_id);
		$has_blocks = $this->db->count_all_results() > 0 ? TRUE : FALSE;
		return $has_candidates || $has_blocks ? TRUE : FALSE;
	}

	public function in_running_election($id)
	{
		$this->db->from('positions');
		$this->db->where('id', $id);
		$this->db->where('election_id IN (SELECT id FROM elections WHERE status = 1)');
		return $this->db->count_all_results() > 0 ? TRUE : FALSE;
	}

	public function for_dropdown($election_id)
	{
		$this->db->from('positions');
		$this->db->where('election_id', $election_id);
		$this->db->order_by('ordinality', 'ASC');
		$query = $this->db->get();
		$tmp = $query->result_array();
		$positions = array();
		foreach ($tmp as $t)
		{
			$positions[$t['id']] = $t['position'];
		}
		return $positions;
	}

}

/* End of file position.php */
/* Location: ./application/models/position.php */
