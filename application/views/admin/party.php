<?php
	if ($action == 'edit')
	{
		$url = 'admin/parties/edit/' . $party['id'];
		$icon = '<i class="icon-pencil"></i>';
	}
	else
	{
		$url = 'admin/parties/add';
		$icon = '<i class="icon-plus"></i>';
	}
?>
<h1><?php echo e('admin_parties_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li><?php echo anchor('admin/parties', '<i class="icon-list"></i> List all'); ?></li>
		<li class="active">
			<?php echo anchor($url, $icon . ' ' . e('admin_' . $action . '_party_submit')); ?>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2><?php echo e('admin_' . $action . '_party_label'); ?></h2>
			<?php echo display_messages($this->session->flashdata('messages')); ?>
			<?php echo form_open_multipart($url, 'class="form-horizontal"'); ?>
				<fieldset>
					<div class="control-group<?php echo form_error('election_id') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_party_election') . ':', 'election_id', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_dropdown('election_id', array('' => 'Select Election') + $elections, set_value('election_id', $party['election_id']), 'id="election_id"'); ?>
							<span class="help-inline"><?php echo form_error('election_id') ? form_error('election_id', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<div class="control-group<?php echo form_error('party') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_party_party') . ':', 'party', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('party', set_value('party', $party['party']), 'id="party" maxlength="63"'); ?>
							<span class="help-inline"><?php echo form_error('party') ? form_error('party', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_party_alias') . ':', 'alias', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('alias', set_value('alias', $party['alias']), 'id="alias" maxlength="15"'); ?>
							<span class="help-inline">(optional)</span>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_party_description') . ':', 'description', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_textarea('description', set_value('description', $party['description']), 'id="description"'); ?>
							<span class="help-inline">(optional)</span>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_party_logo') . ':', 'logo', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_upload('logo', '', 'id="logo"'); ?>
							<span class="help-inline">(optional)</span>
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<?php echo form_submit('submit', e('admin_' . $action . '_party_submit'), 'class="btn btn-primary"') ?>
					<?php echo anchor('admin/parties', 'Cancel', 'class="btn"'); ?>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>