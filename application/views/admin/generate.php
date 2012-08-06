<h1><?php echo e('admin_voters_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li><?php echo anchor('admin/voters', '<i class="icon-list"></i> List all'); ?></li>
		<li><?php echo anchor('admin/voters/add', '<i class="icon-plus"></i> ' . e('admin_voters_add')); ?></li>
		<li><?php echo anchor('admin/voters/import', '<i class="icon-download"></i> Import Voters'); ?></li>
		<li><?php echo anchor('admin/voters/export', '<i class="icon-upload"></i> Export Voters'); ?></li>
		<li class="active"><?php echo anchor('admin/voters/generate', '<i class="icon-wrench"></i> ' . ($this->config->item('halalan_pin') ? 'Generate Passwords and PINs' : 'Generate Passwords')); ?></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2><?php echo $this->config->item('halalan_pin') ? e('admin_generate_password_pin_label') : e('admin_generate_password_label'); ?></h2>
			<?php echo display_messages($this->session->flashdata('messages')); ?>
			<?php echo form_open('admin/voters/generate', 'class="form-horizontal"'); ?>
				<fieldset>
					<div class="control-group<?php echo form_error('block_id') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_generate_block') . ':' , 'block_id', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_dropdown('block_id', array('' => 'Select Block') + $blocks, set_value('block_id'), 'id="block_id"'); ?>
							<span class="help-inline"><?php echo form_error('block_id') ? form_error('block_id', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<?php echo form_submit('submit', e('admin_generate_submit'), 'class="btn btn-primary"') ?>
					<?php echo anchor('admin/voters', 'Cancel', 'class="btn"'); ?>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>