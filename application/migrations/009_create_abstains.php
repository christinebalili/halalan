<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_abstains extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'position_id' => array(
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
		$this->dbforge->add_key(array('position_id', 'voter_id'), TRUE);
		$this->dbforge->add_key('position_id');
		$this->dbforge->add_key('voter_id');
		$this->dbforge->create_table('abstains');
		$this->db->query('ALTER TABLE abstains ADD FOREIGN KEY (position_id) REFERENCES positions (id) ON UPDATE CASCADE ON DELETE RESTRICT');
		$this->db->query('ALTER TABLE abstains ADD FOREIGN KEY (voter_id) REFERENCES voters (id) ON UPDATE CASCADE ON DELETE RESTRICT');
	}

	public function down()
	{
		$this->dbforge->drop_table('abstains');
	}

}

/* End of file 009_create_abstains.php */
/* Location: ./application/migrations/009_create_abstains.php */