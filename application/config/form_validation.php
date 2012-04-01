<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
		'_election' => array(
			array(
				'field' => 'election',
				'label' => e('admin_election_election'),
				'rules' => 'required|max_length[63]|callback__rule_is_existing[elections.election]'
			),
			array(
				'field' => 'description',
				'label' => e('admin_election_description'),
				'rules' => ''
			)
		),
		'_position' => array(
			array(
				'field' => 'election_id',
				'label' => e('admin_position_election'),
				'rules' => 'required|callback__rule_running_election'
			),
			array(
				'field' => 'position',
				'label' => e('admin_position_position'),
				'rules' => 'required|max_length[63]|callback__rule_is_existing[positions.position,election_id]|callback__rule_dependencies'
			),
			array(
				'field' => 'description',
				'label' => e('admin_position_description'),
				'rules' => ''
			),
			array(
				'field' => 'maximum',
				'label' => e('admin_position_maximum'),
				'rules' => 'required|is_natural_no_zero'
			),
			array(
				'field' => 'ordinality',
				'label' => e('admin_position_ordinality'),
				'rules' => 'required|is_natural_no_zero'
			),
			array(
				'field' => 'abstain',
				'label' => e('admin_position_abstain'),
				'rules' => ''
			),
			array(
				'field' => 'unit',
				'label' => e('admin_position_unit'),
				'rules' => ''
			)
		),
		'_party' => array(
			array(
				'field' => 'election_id',
				'label' => e('admin_party_election'),
				'rules' => 'required|callback__rule_running_election'
			),
			array(
				'field' => 'party',
				'label' => e('admin_party_party'),
				'rules' => 'required|max_length[63]|callback__rule_is_existing[parties.party,election_id]|callback__rule_dependencies'
			),
			array(
				'field' => 'alias',
				'label' => e('admin_party_alias'),
				'rules' => 'max_length[15]'
			),
			array(
				'field' => 'description',
				'label' => e('admin_party_description'),
				'rules' => ''
			),
			array(
				'field' => 'logo',
				'label' => e('admin_party_logo'),
				'rules' => 'callback__rule_upload_image'
			)
		),
		'_candidate' => array(
			array(
				'field' => 'first_name',
				'label' => e('admin_candidate_first_name'),
				'rules' => 'required|max_length[63]|callback__rule_dependencies'
			),
			array(
				'field' => 'last_name',
				'label' => e('admin_candidate_last_name'),
				'rules' => 'required|max_length[63]|callback__rule_is_existing[candidates.last_name,first_name,alias,election_id]'
			),
			array(
				'field' => 'alias',
				'label' => e('admin_candidate_alias'),
				'rules' => 'max_length[15]'
			),
			array(
				'field' => 'description',
				'label' => e('admin_candidate_description'),
				'rules' => ''
			),
			array(
				'field' => 'election_id',
				'label' => e('admin_candidate_election'),
				'rules' => 'required|callback__rule_running_election'
			),
			array(
				'field' => 'position_id',
				'label' => e('admin_candidate_position'),
				'rules' => 'required'
			),
			array(
				'field' => 'party_id',
				'label' => e('admin_candidate_party'),
				'rules' => ''
			),
			array(
				'field' => 'picture',
				'label' => e('admin_candidate_picture'),
				'rules' => 'callback__rule_upload_image'
			)
		),
		'_block' => array(
			array(
				'field' => 'block',
				'label' => e('admin_block_block'),
				'rules' => 'required|max_length[63]|callback__rule_is_existing[blocks.block]|callback__rule_dependencies'
			),
			array(
				'field' => 'chosen_elections',
				'label' => e('admin_block_chosen_elections'),
				'rules' => 'required|callback__rule_running_election'
			)
		),
		'_voter_web' => array(
			array(
				'field' => 'username',
				'label' => e('admin_voter_username'),
				'rules' => 'required|max_length[63]|callback__rule_is_existing[voters.username]|callback__rule_dependencies'
			),
			array(
				'field' => 'first_name',
				'label' => e('admin_voter_first_name'),
				'rules' => 'required|max_length[63]'
			),
			array(
				'field' => 'last_name',
				'label' => e('admin_voter_last_name'),
				'rules' => 'required|max_length[63]'
			),
			array(
				'field' => 'block_id',
				'label' => e('admin_voter_block'),
				'rules' => 'required|callback__rule_running_election'
			)
		),
		'_voter_email' => array(
			array(
				'field' => 'username',
				'label' => e('admin_voter_email'),
				'rules' => 'required|max_length[63]|valid_email|callback__rule_is_existing[voters.username]|callback__rule_dependencies'
			),
			array(
				'field' => 'first_name',
				'label' => e('admin_voter_first_name'),
				'rules' => 'required|max_length[63]'
			),
			array(
				'field' => 'last_name',
				'label' => e('admin_voter_last_name'),
				'rules' => 'required|max_length[63]'
			),
			array(
				'field' => 'block_id',
				'label' => e('admin_voter_block'),
				'rules' => 'required|callback__rule_running_election'
			)
		),
		'import' => array(
			array(
				'field' => 'block_id',
				'label' => e('admin_import_block'),
				'rules' => 'required'
			),
			array(
				'field' => 'csv',
				'label' => e('admin_import_csv'),
				'rules' => 'callback__rule_upload_csv'
			)
		),
		'export' => array(
			array(
				'field' => 'block_id',
				'label' => e('admin_export_block'),
				'rules' => 'required'
			)
		)
	);


/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */