<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_blocks extends CI_Migration {

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
			'block' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'description' => array(
				'type' => 'TEXT',
				'null' => TRUE
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('election_id');
		$this->dbforge->add_key('block');
		$this->dbforge->create_table('blocks');
		$this->db->query('ALTER TABLE blocks ADD UNIQUE (election_id, block)');
		$this->db->query('ALTER TABLE blocks ADD FOREIGN KEY (election_id) REFERENCES elections (id) ON UPDATE CASCADE ON DELETE RESTRICT');

		$this->dbforge->add_field(array(
			'block_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE
			),
			'position_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE
			)
		));
		$this->dbforge->add_key(array('block_id', 'position_id'), TRUE);
		$this->dbforge->add_key('block_id');
		$this->dbforge->add_key('position_id');
		$this->dbforge->create_table('blocks_positions');
		$this->db->query('ALTER TABLE blocks_positions ADD FOREIGN KEY (block_id) REFERENCES blocks (id) ON UPDATE CASCADE ON DELETE RESTRICT');
		$this->db->query('ALTER TABLE blocks_positions ADD FOREIGN KEY (position_id) REFERENCES positions (id) ON UPDATE CASCADE ON DELETE RESTRICT');
	}

	public function down()
	{
		$this->dbforge->drop_table('blocks_positions');
		$this->dbforge->drop_table('blocks');
	}

}

/* End of file 006_create_blocks.php */
/* Location: ./application/migrations/006_create_blocks.php */