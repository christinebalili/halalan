<?php
	if ($action == 'edit')
	{
		$url = 'admin/elections/edit/' . $election['id'];
		$icon = '<i class="icon-pencil"></i>';
	}
	else
	{
		$url = 'admin/elections/add';
		$icon = '<i class="icon-plus"></i>';
	}
?>
<h1><?php echo e('admin_elections_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li><?php echo anchor('admin/elections', '<i class="icon-list"></i> List all'); ?></li>
		<li class="active">
			<?php echo anchor($url, $icon . ' ' . e('admin_' . $action . '_election_submit')); ?>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2><?php echo e('admin_' . $action . '_election_label'); ?></h2>
			<?php echo display_messages($this->session->flashdata('messages')); ?>
			<?php echo form_open($url, 'class="form-horizontal"'); ?>
				<fieldset>
					<div class="control-group<?php echo form_error('election') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_election_election') . ':', 'election', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('election', set_value('election', $election['election']), 'id="election" maxlength="63"'); ?>
							<span class="help-inline"><?php echo form_error('election') ? form_error('election', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_election_description') . ':', 'description', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_textarea('description', set_value('description', $election['description']), 'id="description"'); ?>
							<span class="help-inline">(optional)</span>
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<?php echo form_submit('submit', e('admin_' . $action . '_election_submit'), 'class="btn btn-primary"') ?>
					<?php echo anchor('admin/elections', 'Cancel', 'class="btn"'); ?>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>