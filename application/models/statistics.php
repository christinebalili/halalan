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

class Statistics extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function breakdown_all_voters($election_id)
	{
		$this->db->select('block, COUNT(distinct voters.id) AS count');
		$this->db->from('blocks');
		$this->db->join('blocks_elections_positions', 'blocks_elections_positions.block_id = blocks.id', 'left');
		$this->db->join('voters', 'voters.block_id = blocks_elections_positions.block_id', 'left');
		$this->db->where('election_id', $election_id);
		$this->db->group_by('block');
		$this->db->order_by('block', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function breakdown_all_voted($election_id)
	{
		$this->db->select('block, COUNT(distinct voted.voter_id) AS count');
		$this->db->from('blocks');
		$this->db->join('blocks_elections_positions', 'blocks_elections_positions.block_id = blocks.id', 'left');
		$this->db->join('voters', 'voters.block_id = blocks_elections_positions.block_id', 'left');
		$this->db->join('voted', 'voted.voter_id = voters.id', 'left');
		$this->db->where('blocks_elections_positions.election_id', $election_id);
		$this->db->group_by('block');
		$this->db->order_by('block', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function breakdown_all_by_duration($election_id, $begin, $end)
	{
		$this->db->select('block, COUNT(distinct voters.id) AS count');
		$this->db->from('blocks');
		$this->db->join('blocks_elections_positions', 'blocks_elections_positions.block_id = blocks.id', 'left');
		if ($end)
		{
			$this->db->join('voters', 'voters.block_id = blocks_elections_positions.block_id AND timediff(logout, login) >= \'' . $begin . '\' AND timediff(logout, login) < \'' . $end . '\'', 'left');
		}
		else
		{
			$this->db->join('voters', 'voters.block_id = blocks_elections_positions.block_id AND timediff(logout, login) >= \'' . $begin . '\'', 'left');
		}
		$this->db->where('election_id', $election_id);
		$this->db->group_by('block');
		$this->db->order_by('block', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function count_all_voters($election_id)
	{
		$this->db->distinct();
		$this->db->select('id');
		$this->db->from('voters');
		$this->db->join('blocks_elections_positions', 'blocks_elections_positions.block_id = voters.block_id');
		$this->db->where('election_id', $election_id);
		$query = $this->db->get();
		return count($query->result_array());
	}

	public function count_all_voted($election_id)
	{
		$this->db->from('voted');
		$this->db->where('election_id', $election_id);
		return $this->db->count_all_results();
	}

	public function count_all_by_duration($election_id, $begin, $end)
	{
		$this->db->distinct();
		$this->db->select('id');
		$this->db->from('voters');
		$this->db->join('blocks_elections_positions', 'blocks_elections_positions.block_id = voters.block_id');
		$this->db->where('election_id', $election_id);
		$this->db->where('timediff(logout, login) >= \'' . $begin . '\'');
		if ($end)
		{
			$this->db->where('timediff(logout, login) < \'' . $end . '\'');
		}
		$query = $this->db->get();
		return count($query->result_array());
	}

}

/* End of file statistics.php */
/* Location: ./application/models/statistics.php */
