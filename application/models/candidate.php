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

class Candidate extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function insert($candidate)
	{
		return $this->db->insert('candidates', $candidate);
	}

	public function update($candidate, $id)
	{
		return $this->db->update('candidates', $candidate, array('id' => $id));
	}

	public function delete($id)
	{
		return $this->db->delete('candidates', array('id' => $id));
	}

	public function select($id)
	{
		$this->db->where('id', $id);
		$this->db->from('candidates');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function select_all_by_election_id_and_position_id($election_id, $position_id)
	{
		$this->db->from('candidates');
		$this->db->where('election_id', $election_id);
		$this->db->where('position_id', $position_id);
		$this->db->order_by('party_id', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function in_use($candidate_id)
	{
		$this->db->from('votes');
		$this->db->where('candidate_id', $candidate_id);
		return $this->db->count_all_results() > 0 ? TRUE : FALSE;
	}

	public function in_running_election($id)
	{
		$this->db->from('candidates');
		$this->db->where('id', $id);
		$this->db->where('election_id IN (SELECT id FROM elections WHERE status = 1)');
		return $this->db->count_all_results() > 0 ? TRUE : FALSE;
	}

}

/* End of file candidate.php */
/* Location: ./application/models/candidate.php */
