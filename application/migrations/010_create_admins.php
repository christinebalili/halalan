<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_admins extends CI_Migration {

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
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'type' => array(
				'type' => 'ENUM',
				'constraint' => "'master','admin','staff'"
			),
			'election_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'null' => TRUE
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('name');
		$this->dbforge->create_table('admins');
		$this->db->query('ALTER TABLE admins ADD UNIQUE (username)');
		$this->db->query('ALTER TABLE admins ADD UNIQUE (email)');
		$this->db->query('ALTER TABLE admins ADD FOREIGN KEY (election_id) REFERENCES elections (id) ON UPDATE CASCADE ON DELETE RESTRICT');
	}

	public function down()
	{
		$this->dbforge->drop_table('admins');
	}

}