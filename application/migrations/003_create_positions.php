<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_positions extends CI_Migration {

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
			'position' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'description' => array(
				'type' => 'TEXT',
				'null' => TRUE
			),
			'maximum' => array(
				'type' => 'SMALLINT',
				'unsigned' => TRUE
			),
			'abstain' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
				'default' => 1
			),
			'weight' => array(
				'type' => 'TINYINT',
				'unsigned' => TRUE,
				'default' => 0
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('election_id');
		$this->dbforge->add_key('position');
		$this->dbforge->create_table('positions');
		$this->db->query('ALTER TABLE positions ADD UNIQUE (election_id, position)');
		$this->db->query('ALTER TABLE positions ADD FOREIGN KEY (election_id) REFERENCES elections (id) ON UPDATE CASCADE ON DELETE RESTRICT');
	}

	public function down()
	{
		$this->dbforge->drop_table('positions');
	}

}

/* End of file 003_create_positions.php */
/* Location: ./application/migrations/003_create_positions.php */