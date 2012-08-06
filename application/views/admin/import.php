<h1><?php echo e('admin_voters_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li><?php echo anchor('admin/voters', '<i class="icon-list"></i> List all'); ?></li>
		<li><?php echo anchor('admin/voters/add', '<i class="icon-plus"></i> ' . e('admin_voters_add')); ?></li>
		<li class="active"><?php echo anchor('admin/voters/import', '<i class="icon-download"></i> Import Voters'); ?></li>
		<li><?php echo anchor('admin/voters/export', '<i class="icon-upload"></i> Export Voters'); ?></li>
		<li><?php echo anchor('admin/voters/generate', '<i class="icon-wrench"></i> ' . ($this->config->item('halalan_pin') ? 'Generate Passwords and PINs' : 'Generate Passwords')); ?></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2><?php echo e('admin_import_label'); ?></h2>
			<?php echo display_messages($this->session->flashdata('messages')); ?>
			<?php echo form_open('admin/voters/import', 'class="form-horizontal"'); ?>
				<fieldset>
					<div class="control-group<?php echo form_error('block_id') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_import_block') . ':' , 'block_id', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_dropdown('block_id', array('' => 'Select Block') + $blocks, set_value('block_id'), 'id="block_id"'); ?>
							<span class="help-inline"><?php echo form_error('block_id') ? form_error('block_id', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group<?php echo form_error('csv') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_import_csv') . ':', 'csv', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_upload('csv', '', 'id="csv"'); ?>
							<span class="help-inline"><?php echo form_error('csv') ? form_error('csv', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_import_sample') . ':' , '', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php if ($this->config->item('halalan_password_pin_generation') == 'web'): ?>
							Username,Last Name,First Name<br />
							user1,Suzumiya,Haruhi<br />
							user2,Izumi,Konata<br />
							user3,Etoh,Mei
							<?php elseif ($this->config->item('halalan_password_pin_generation') == 'email'): ?>
							Email,Last Name,First Name<br />
							user1@example.com,Suzumiya,Haruhi<br />
							user2@example.com,Izumi,Konata<br />
							user3@example.com,Etoh,Mei
							<?php endif; ?>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_import_notes') . ':' , '', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php if ($this->config->item('halalan_password_pin_generation') == 'web'): ?>
							Username
							<?php elseif ($this->config->item('halalan_password_pin_generation') == 'email'): ?>
							Email
							<?php endif; ?>
							must be unique.<br />
							Incomplete data will be disregarded.<br />
							Passwords <?php echo $this->config->item('halalan_pin') ? 'and pins ' : ''; ?>are not yet generated.<br />
							Use <?php echo anchor('admin/voters/generate', ($this->config->item('halalan_pin') ? 'Generate Passwords and PINs' : 'Generate Passwords')); ?> to generate passwords<?php echo $this->config->item('halalan_pin') ? ' and pins' : ''; ?>.
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<?php echo form_submit('submit', e('admin_import_submit'), 'class="btn btn-primary"') ?>
					<?php echo anchor('admin/voters', 'Cancel', 'class="btn"'); ?>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>