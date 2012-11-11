<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tables extends CI_Migration {

	public function up()
	{
		$sql = "CREATE TABLE IF NOT EXISTS  `sessions` (
				session_id varchar(40) DEFAULT '0' NOT NULL,
				ip_address varchar(45) DEFAULT '0' NOT NULL,
				user_agent varchar(120) NOT NULL,
				last_activity int(10) unsigned DEFAULT 0 NOT NULL,
				user_data text NOT NULL,
				PRIMARY KEY (session_id),
				KEY `last_activity_idx` (`last_activity`)
			)";
		$this->db->query($sql);
		$sql = "CREATE TABLE captchas (
				captcha_id bigint(13) unsigned NOT NULL auto_increment,
				captcha_time int(10) unsigned NOT NULL,
				ip_address varchar(16) default '0' NOT NULL,
				word varchar(20) NOT NULL,
				PRIMARY KEY `captcha_id` (`captcha_id`),
				KEY `word` (`word`)
			)";
		$this->db->query($sql);
	}

	public function down()
	{
		$this->dbforge->drop_table('captchas');
		$this->dbforge->drop_table('sessions');
	}

}

/* End of file 001_create_tables.php */
/* Location: ./application/migrations/001_create_tables.php */