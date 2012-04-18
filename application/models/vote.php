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

class Vote extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function insert($vote)
	{
		return $this->db->insert('votes', $vote);
	}

	public function breakdown($election_id, $candidate_id)
	{
		$this->db->select('block, COUNT(distinct votes.voter_id) AS count');
		$this->db->from('blocks');
		$this->db->join('blocks_elections_positions', 'blocks_elections_positions.block_id = blocks.id AND blocks_elections_positions.election_id = ' . $election_id);
		$this->db->join('voters', 'voters.block_id = blocks_elections_positions.block_id', 'left');
		$this->db->join('votes', 'votes.voter_id = voters.id AND votes.candidate_id = ' . $candidate_id, 'left');
		$this->db->group_by('block');
		$this->db->order_by('block', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function count_all_by_election_id_and_position_id($election_id, $position_id)
	{
		$this->db->select('count(votes.candidate_id) AS votes, candidates.id AS candidate_id');
		$this->db->from('votes');
		$this->db->join('candidates', 'candidates.id = votes.candidate_id', 'right');
		$this->db->where('election_id', $election_id);
		$this->db->where('position_id', $position_id);
		$this->db->group_by('candidates.id');
		$this->db->order_by('votes', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function select_all_by_voter_id($voter_id)
	{
		$this->db->from('votes');
		$this->db->join('candidates', 'candidates.id = votes.candidate_id');
		$this->db->where('voter_id', $voter_id);
		$this->db->order_by('last_name', 'ASC');
		$this->db->order_by('first_name', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

}

/* End of file vote.php */
/* Location: ./application/models/vote.php */
