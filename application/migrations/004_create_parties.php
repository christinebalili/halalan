<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_parties extends CI_Migration {

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
			'party' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'alias' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE
			),
			'description' => array(
				'type' => 'TEXT',
				'null' => TRUE
			),
			'logo' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('election_id');
		$this->dbforge->add_key('party');
		$this->dbforge->create_table('parties');
		$this->db->query('ALTER TABLE parties ADD UNIQUE (election_id, party)');
		$this->db->query('ALTER TABLE parties ADD FOREIGN KEY (election_id) REFERENCES elections (id) ON UPDATE CASCADE ON DELETE RESTRICT');
	}

	public function down()
	{
		$this->dbforge->drop_table('parties');
	}

}

/* End of file 004_create_parties.php */
/* Location: ./application/migrations/004_create_parties.php */