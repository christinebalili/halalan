CREATE TABLE elections (
  id integer unsigned NOT NULL auto_increment,
  election varchar(63) NOT NULL,
  description text,
  status boolean DEFAULT 0 NOT NULL,
  results boolean DEFAULT 0 NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (election)
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE positions (
  id integer unsigned NOT NULL auto_increment,
  election_id integer unsigned NOT NULL,
  position varchar(63) NOT NULL,
  description text,
  maximum smallint unsigned NOT NULL,
  ordinality smallint unsigned NOT NULL,
  abstain boolean NOT NULL,
  unit boolean NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (election_id, position),
  KEY (position),
  KEY (election_id),
  FOREIGN KEY (election_id) REFERENCES elections (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE parties (
  id integer unsigned NOT NULL auto_increment,
  election_id integer unsigned NOT NULL,
  party varchar(63) NOT NULL,
  alias varchar(15),
  description text,
  logo varchar(63),
  PRIMARY KEY (id),
  UNIQUE KEY (election_id, party),
  KEY (party),
  KEY (election_id),
  FOREIGN KEY (election_id) REFERENCES elections (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE candidates (
  id integer unsigned NOT NULL auto_increment,
  first_name varchar(63) NOT NULL,
  last_name varchar(63) NOT NULL,
  alias varchar(15),
  election_id integer unsigned NOT NULL,
  position_id integer unsigned NOT NULL,
  party_id integer unsigned,
  description text,
  picture varchar(63),
  PRIMARY KEY (id),
  UNIQUE KEY (election_id, first_name, last_name, alias),
  KEY (election_id, position_id),
  KEY (first_name, last_name),
  KEY (first_name, last_name, alias),
  KEY (election_id),
  FOREIGN KEY (election_id) REFERENCES elections (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  KEY (position_id),
  FOREIGN KEY (position_id) REFERENCES positions (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  KEY (party_id),
  FOREIGN KEY (party_id) REFERENCES parties (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE blocks (
  id integer unsigned NOT NULL auto_increment,
  block varchar(63) NOT NULL,
  description text,
  PRIMARY KEY (id),
  UNIQUE KEY (block)
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE blocks_elections_positions (
  block_id integer unsigned NOT NULL,
  election_id integer unsigned NOT NULL,
  position_id integer unsigned NOT NULL,
  PRIMARY KEY (block_id, election_id, position_id),
  KEY (block_id),
  FOREIGN KEY (block_id) REFERENCES blocks (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  KEY (election_id),
  FOREIGN KEY (election_id) REFERENCES elections (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  KEY (position_id),
  FOREIGN KEY (position_id) REFERENCES positions (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE voters (
  id integer unsigned NOT NULL auto_increment,
  username varchar(63) NOT NULL,
  password char(40) NOT NULL,
  pin char(40),
  first_name varchar(63) NOT NULL,
  last_name varchar(63) NOT NULL,
  block_id integer unsigned NOT NULL,
  login datetime,
  logout datetime,
  ip_address integer,
  PRIMARY KEY (id),
  UNIQUE KEY (username),
  KEY (first_name, last_name),
  KEY (block_id),
  FOREIGN KEY (block_id) REFERENCES blocks (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE votes (
  candidate_id integer unsigned NOT NULL,
  voter_id integer unsigned NOT NULL,
  timestamp datetime NOT NULL,
  PRIMARY KEY (candidate_id,voter_id),
  KEY (voter_id),
  FOREIGN KEY (voter_id) REFERENCES voters (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  KEY (candidate_id),
  FOREIGN KEY (candidate_id) REFERENCES candidates (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE abstains (
  election_id integer unsigned NOT NULL,
  position_id integer unsigned NOT NULL,
  voter_id integer unsigned NOT NULL,
  timestamp datetime NOT NULL,
  PRIMARY KEY (election_id, position_id, voter_id),
  KEY (election_id),
  FOREIGN KEY (election_id) REFERENCES elections (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  KEY (position_id),
  FOREIGN KEY (position_id) REFERENCES positions (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  KEY (voter_id),
  FOREIGN KEY (voter_id) REFERENCES voters (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE voted (
  election_id integer unsigned NOT NULL,
  voter_id integer unsigned NOT NULL,
  image_trail_hash char(40),
  timestamp datetime NOT NULL,
  PRIMARY KEY (election_id, voter_id),
  KEY (election_id),
  FOREIGN KEY (election_id) REFERENCES elections (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  KEY (voter_id),
  FOREIGN KEY (voter_id) REFERENCES voters (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE admins (
  id integer unsigned NOT NULL auto_increment,
  email varchar(63) NOT NULL,
  username varchar(63) NOT NULL,
  password char(40) NOT NULL,
  first_name varchar(63) NOT NULL,
  last_name varchar(63) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (username)
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE sessions (
    session_id varchar(40) DEFAULT '0' NOT NULL,
    ip_address varchar(45) DEFAULT '0' NOT NULL,
    user_agent varchar(120) NOT NULL,
    last_activity int(10) unsigned DEFAULT 0 NOT NULL,
    user_data text NOT NULL,
    PRIMARY KEY (session_id),
    KEY last_activity_idx (last_activity)
);

CREATE TABLE captchas (
    captcha_id bigint(13) unsigned NOT NULL auto_increment,
    captcha_time int(10) unsigned NOT NULL,
    ip_address varchar(16) default '0' NOT NULL,
    word varchar(20) NOT NULL,
    PRIMARY KEY captcha_id (captcha_id),
    KEY word (word)
);