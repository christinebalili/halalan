<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_voters extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'username' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'password' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'pin' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'login' => array(
				'type' => 'DATETIME',
				'null' => TRUE
			),
			'logout' => array(
				'type' => 'DATETIME',
				'null' => TRUE
			),
			'ip_address' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'null' => TRUE
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('name');
		$this->dbforge->create_table('voters');
		$this->db->query('ALTER TABLE voters ADD UNIQUE (username)');

		$this->dbforge->add_field(array(
			'voter_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE
			),
			'election_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE
			),
			'block_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE
			),
			'voted' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
				'default' => 0
			),
			'image_trail_hash' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE
			),
			'timestamp' => array(
				'type' => 'DATETIME',
				'null' => TRUE
			)
		));
		$this->dbforge->add_key(array('voter_id', 'election_id', 'block_id'), TRUE);
		$this->dbforge->add_key('block_id');
		$this->dbforge->add_key('election_id');
		$this->dbforge->add_key('voter_id');
		$this->dbforge->create_table('blocks_elections_voters');
		$this->db->query('ALTER TABLE blocks_elections_voters ADD FOREIGN KEY (block_id) REFERENCES blocks (id) ON UPDATE CASCADE ON DELETE RESTRICT');
		$this->db->query('ALTER TABLE blocks_elections_voters ADD FOREIGN KEY (election_id) REFERENCES elections (id) ON UPDATE CASCADE ON DELETE RESTRICT');
		$this->db->query('ALTER TABLE blocks_elections_voters ADD FOREIGN KEY (voter_id) REFERENCES voters (id) ON UPDATE CASCADE ON DELETE RESTRICT');
	}

	public function down()
	{
		$this->dbforge->drop_table('blocks_elections_voters');
		$this->dbforge->drop_table('voters');
	}

}

/* End of file 007_create_voters.php */
/* Location: ./application/migrations/007_create_voters.php */