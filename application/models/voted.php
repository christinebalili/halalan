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

class Voted extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function insert($voted)
	{
		return $this->db->insert('voted', $voted);
	}

	public function update($voted, $election_id, $voter_id)
	{
		return $this->db->update('voted', $voted, array('election_id' => $election_id, 'voter_id' => $voter_id));
	}

	public function select($election_id, $voter_id)
	{
		$this->db->from('voted');
		$this->db->where('election_id', $election_id);
		$this->db->where('voter_id', $voter_id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function select_all_by_voter_id($voter_id)
	{
		$this->db->from('voted');
		$this->db->where('voter_id', $voter_id);
		$query = $this->db->get();
		return $query->result_array();
	}

}

/* End of file voted.php */
/* Location: ./application/models/voted.php */
