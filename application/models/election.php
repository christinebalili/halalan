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

class Election extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function insert($election)
	{
		return $this->db->insert('elections', $election);
	}

	public function update($election, $id)
	{
		return $this->db->update('elections', $election, array('id' => $id));
	}

	public function delete($id)
	{
		return $this->db->delete('elections', array('id' => $id));
	}

	public function select($id)
	{
		$this->db->from('elections');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function select_all()
	{
		$this->db->from('elections');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function select_all_by_ids($ids)
	{
		$this->db->from('elections');
		$this->db->where_in('id', $ids);
		$query = $this->db->get();
		return $query->result_array();
	}

	// elections with results should not be running
	public function select_all_with_results()
	{
		$this->db->from('elections');
		$this->db->where('results', TRUE);
		$this->db->where('status', FALSE); // not running
		$query = $this->db->get();
		return $query->result_array();
	}

	public function in_use($election_id)
	{
		$this->db->from('positions');
		$this->db->where('election_id', $election_id);
		$has_positions = $this->db->count_all_results() > 0 ? TRUE : FALSE;
		$this->db->from('parties');
		$this->db->where('election_id', $election_id);
		$has_parties = $this->db->count_all_results() > 0 ? TRUE : FALSE;
		return $has_positions || $has_parties ? TRUE : FALSE;
	}

	public function is_running($id)
	{
		$this->db->from('elections');
		$this->db->where('status', TRUE);
		$this->db->where('id', $id);
		return $this->db->count_all_results() > 0 ? TRUE : FALSE;
	}

	public function are_running($ids)
	{
		$this->db->from('elections');
		$this->db->where('status', TRUE);
		$this->db->where_in('id', $ids);
		return $this->db->count_all_results() > 0 ? TRUE : FALSE;
	}

	public function for_dropdown()
	{
		$this->db->from('elections');
		$this->db->order_by('election', 'ASC');
		$query = $this->db->get();
		$tmp = $query->result_array();
		$elections = array();
		foreach ($tmp as $t)
		{
			$elections[$t['id']] = $t['election'];
		}
		return $elections;
	}

}

/* End of file election.php */
/* Location: ./application/models/election.php */
