<?php
	if ($action == 'edit')
	{
		$url = 'admin/positions/edit/' . $position['id'];
		$icon = '<i class="icon-pencil"></i>';
	}
	else
	{
		$url = 'admin/positions/add';
		$icon = '<i class="icon-plus"></i>';
	}
?>
<h1><?php echo e('admin_positions_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li><?php echo anchor('admin/positions', '<i class="icon-list"></i> List all'); ?></li>
		<li class="active">
			<?php echo anchor($url, $icon . ' ' . e('admin_' . $action . '_position_submit')); ?>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2><?php echo e('admin_' . $action . '_position_label'); ?></h2>
			<?php echo display_messages($this->session->flashdata('messages')); ?>
			<?php echo form_open($url, 'class="form-horizontal"'); ?>
				<fieldset>
					<div class="control-group<?php echo form_error('election_id') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_position_election') . ':', 'election_id', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_dropdown('election_id', array('' => 'Select Election') + $elections, set_value('election_id', $position['election_id']), 'id="election_id"'); ?>
							<span class="help-inline"><?php echo form_error('election_id') ? form_error('election_id', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<div class="control-group<?php echo form_error('position') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_position_position') . ':', 'position', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('position', set_value('position', $position['position']), 'id="position" maxlength="63"'); ?>
							<span class="help-inline"><?php echo form_error('position') ? form_error('position', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_position_description') . ':', 'description', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_textarea('description', set_value('description', $position['description']), 'id="description"'); ?>
							<span class="help-inline">(optional)</span>
						</div>
					</div>
					<div class="control-group<?php echo form_error('maximum') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_position_maximum') . ':', 'maximum', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('maximum', set_value('maximum', $position['maximum']), 'id="maximum"'); ?>
							<span class="help-inline"><?php echo form_error('maximum') ? form_error('maximum', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group<?php echo form_error('ordinality') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_position_ordinality') . ':', 'ordinality', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('ordinality', set_value('ordinality', $position['ordinality']), 'id="ordinality"'); ?>
							<span class="help-inline"><?php echo form_error('ordinality') ? form_error('ordinality', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_position_abstain') . ':', 'abstain', array('class' => 'control-label')); ?>
						<div class="controls">
							<label class="radio inline">
								<?php echo form_radio('abstain', '1', set_value('abstain', $position['abstain']) == 1 ? TRUE : FALSE, 'id="yes"'); ?>
								Yes
							</label>
							<label class="radio inline">
								<?php echo form_radio('abstain', '0', set_value('abstain', $position['abstain']) == 0 ? TRUE : FALSE, 'id="no"'); ?>
								No
							</label>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_position_unit') . ':', 'unit', array('class' => 'control-label')); ?>
						<div class="controls">
							<label class="radio inline">
								<?php echo form_radio('unit', '0', set_value('unit', $position['unit']) == 0 ? TRUE : FALSE, 'id="general"'); ?>
								General
							</label>
							<label class="radio inline">
								<?php echo form_radio('unit', '1', set_value('unit', $position['unit']) == 1 ? TRUE : FALSE, 'id="specific"'); ?>
								Specific
							</label>
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<?php echo form_submit('submit', e('admin_' . $action . '_position_submit'), 'class="btn btn-primary"') ?>
					<?php echo anchor('admin/positions', 'Cancel', 'class="btn"'); ?>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>