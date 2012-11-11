<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_candidates extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'election_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE
			),
			'position_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE
			),
			'party_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'null' => TRUE
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'description' => array(
				'type' => 'TEXT',
				'null' => TRUE
			),
			'picture' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('election_id');
		$this->dbforge->add_key('position_id');
		$this->dbforge->add_key('party_id');
		$this->dbforge->add_key('name');
		$this->dbforge->create_table('candidates');
		$this->db->query('ALTER TABLE candidates ADD KEY (election_id, position_id)');
		$this->db->query('ALTER TABLE candidates ADD UNIQUE (election_id, name)');
		$this->db->query('ALTER TABLE candidates ADD FOREIGN KEY (election_id) REFERENCES elections (id) ON UPDATE CASCADE ON DELETE RESTRICT');
		$this->db->query('ALTER TABLE candidates ADD FOREIGN KEY (position_id) REFERENCES positions (id) ON UPDATE CASCADE ON DELETE RESTRICT');
		$this->db->query('ALTER TABLE candidates ADD FOREIGN KEY (party_id) REFERENCES parties (id) ON UPDATE CASCADE ON DELETE RESTRICT');
	}

	public function down()
	{
		$this->dbforge->drop_table('candidates');
	}

}

/* End of file 005_create_candidates.php */
/* Location: ./application/migrations/005_create_candidates.php */