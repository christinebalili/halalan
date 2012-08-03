<?php
	if ($action == 'edit')
	{
		$url = 'admin/candidates/edit/' . $candidate['id'];
		$icon = '<i class="icon-pencil"></i>';
	}
	else
	{
		$url = 'admin/candidates/add';
		$icon = '<i class="icon-plus"></i>';
	}
?>
<h1><?php echo e('admin_candidates_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li><?php echo anchor('admin/candidates', '<i class="icon-list"></i> List all'); ?></li>
		<li class="active">
			<?php echo anchor($url, $icon . ' ' . e('admin_' . $action . '_candidate_submit')); ?>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2><?php echo e('admin_' . $action . '_candidate_label'); ?></h2>
			<?php echo display_messages($this->session->flashdata('messages')); ?>
			<?php echo form_open_multipart($url, 'class="form-horizontal"'); ?>
				<fieldset>
					<div class="control-group<?php echo form_error('election_id') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_candidate_election') . ':', 'election_id', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_dropdown('election_id', array('' => 'Select Election') + $elections, set_value('election_id', $candidate['election_id']), 'id="election_id" class="fillPositionsAndParties"'); ?>
							<span class="help-inline"><?php echo form_error('election_id') ? form_error('election_id', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group<?php echo form_error('position_id') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_candidate_position') . ':', 'position_id', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_dropdown('position_id', array('' => 'Select Position') + $positions, set_value('position_id', $candidate['position_id']), 'id="position_id"'); ?>
							<span class="help-inline"><?php echo form_error('position_id') ? form_error('position_id', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<div class="control-group">
						<?php echo form_label(e('admin_candidate_party') . ':', 'party_id', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_dropdown('party_id', array('' => 'Select Party') + $parties, set_value('party_id', $candidate['party_id']), 'id="party_id"'); ?>
							<span class="help-inline">(optional)</span>
						</div>
					</div>
					<div class="control-group<?php echo form_error('first_name') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_candidate_first_name') . ':', 'first_name', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('first_name', set_value('first_name', $candidate['first_name']), 'id="first_name" maxlength="63"'); ?>
							<span class="help-inline"><?php echo form_error('first_name') ? form_error('first_name', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group<?php echo form_error('last_name') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_candidate_last_name') . ':', 'last_name', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('last_name', set_value('last_name', $candidate['last_name']), 'id="last_name" maxlength="63"'); ?>
							<span class="help-inline"><?php echo form_error('last_name') ? form_error('last_name', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_candidate_alias') . ':', 'alias', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('alias', set_value('alias', $candidate['alias']), 'id="alias" maxlength="15"'); ?>
							<span class="help-inline">(optional)</span>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_candidate_description') . ':', 'description', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_textarea('description', set_value('description', $candidate['description']), 'id="description"'); ?>
							<span class="help-inline">(optional)</span>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_candidate_picture') . ':', 'picture', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_upload('picture', '', 'id="picture"'); ?>
							<span class="help-inline">(optional)</span>
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<?php echo form_submit('submit', e('admin_' . $action . '_election_submit'), 'class="btn btn-primary"') ?>
					<?php echo anchor('admin/candidates', 'Cancel', 'class="btn"'); ?>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>