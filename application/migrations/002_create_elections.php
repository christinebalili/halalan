<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_elections extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'election' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'description' => array(
				'type' => 'TEXT',
				'null' => TRUE
			),
			'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
				'default' => 0
			),
			'results' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
				'default' => 0
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('elections');
		$this->db->query('ALTER TABLE elections ADD UNIQUE (election)');
	}

	public function down()
	{
		$this->dbforge->drop_table('elections');
	}

}

/* End of file 002_create_elections.php */
/* Location: ./application/migrations/002_create_elections.php */