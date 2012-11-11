<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_votes extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'candidate_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE
			),
			'voter_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE
			),
			'timestamp' => array(
				'type' => 'DATETIME'
			)
		));
		$this->dbforge->add_key(array('candidate_id', 'voter_id'), TRUE);
		$this->dbforge->add_key('candidate_id');
		$this->dbforge->add_key('voter_id');
		$this->dbforge->create_table('votes');
		$this->db->query('ALTER TABLE votes ADD FOREIGN KEY (candidate_id) REFERENCES candidates (id) ON UPDATE CASCADE ON DELETE RESTRICT');
		$this->db->query('ALTER TABLE votes ADD FOREIGN KEY (voter_id) REFERENCES voters (id) ON UPDATE CASCADE ON DELETE RESTRICT');
	}

	public function down()
	{
		$this->dbforge->drop_table('votes');
	}

}

/* End of file 008_create_votes.php */
/* Location: ./application/migrations/008_create_votes.php */