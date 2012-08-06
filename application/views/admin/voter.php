<?php
	if ($action == 'edit')
	{
		$url = 'admin/voters/edit/' . $voter['id'];
		$icon = '<i class="icon-pencil"></i>';
	}
	else
	{
		$url = 'admin/voters/add';
		$icon = '<i class="icon-plus"></i>';
	}
?>
<h1><?php echo e('admin_voters_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li><?php echo anchor('admin/voters', '<i class="icon-list"></i> List all'); ?></li>
		<li class="active">
			<?php echo anchor($url, $icon . ' ' . e('admin_' . $action . '_voter_submit')); ?>
		</li>
		<li><?php echo anchor('admin/voters/import', '<i class="icon-download"></i> Import Voters'); ?></li>
		<li><?php echo anchor('admin/voters/export', '<i class="icon-upload"></i> Export Voters'); ?></li>
		<li><?php echo anchor('admin/voters/generate', '<i class="icon-wrench"></i> ' . ($this->config->item('halalan_pin') ? 'Generate Passwords and PINs' : 'Generate Passwords')); ?></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2><?php echo e('admin_' . $action . '_voter_label'); ?></h2>
			<?php echo display_messages($this->session->flashdata('messages')); ?>
			<?php echo form_open($url, 'class="form-horizontal"'); ?>
				<fieldset>
					<div class="control-group<?php echo form_error('block_id') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_export_block') . ':' , 'block_id', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_dropdown('block_id', array('' => 'Select Block') + $blocks, set_value('block_id', $voter['block_id']), 'id="block_id"'); ?>
							<span class="help-inline"><?php echo form_error('block_id') ? form_error('block_id', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group<?php echo form_error('username') ? ' error' : ''; ?>">
						<?php echo form_label($this->config->item('halalan_password_pin_generation') == 'email' ? e('admin_voter_email') : e('admin_voter_username') . ':', 'username', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('username', set_value('username', $voter['username']), 'id="username" maxlength="63"'); ?>
							<span class="help-inline"><?php echo form_error('username') ? form_error('username', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group<?php echo form_error('first_name') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_voter_first_name') . ':', 'first_name', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('first_name', set_value('first_name', $voter['first_name']), 'id="first_name" maxlength="63"'); ?>
							<span class="help-inline"><?php echo form_error('first_name') ? form_error('first_name', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group<?php echo form_error('last_name') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_voter_last_name') . ':', 'last_name', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('last_name', set_value('last_name', $voter['last_name']), 'id="last_name" maxlength="63"'); ?>
							<span class="help-inline"><?php echo form_error('last_name') ? form_error('last_name', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<?php if ($action == 'edit'): ?>
					<div class="control-group">
						<?php echo form_label(e('admin_voter_regenerate') . ':', 'regenerate', array('class' => 'control-label')); ?>
						<div class="controls">
							<label class="checkbox inline">
								<?php echo form_checkbox('password', TRUE, FALSE, 'id="password"'); ?>				
								&nbsp;<?php echo e('admin_voter_password'); ?>
							</label>
							<?php if ($this->config->item('halalan_pin')): ?>
							<label class="checkbox inline">
								<?php echo form_checkbox('pin', TRUE, FALSE, 'id="pin"'); ?>				
								&nbsp;<?php echo e('admin_voter_pin'); ?>
							</label>
							<?php endif; ?>
						</div>
					</div>
					<?php endif; ?>
				</fieldset>
				<div class="form-actions">
					<?php echo form_submit('submit', e('admin_' . $action . '_voter_submit'), 'class="btn btn-primary"') ?>
					<?php echo anchor('admin/voters', 'Cancel', 'class="btn"'); ?>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>