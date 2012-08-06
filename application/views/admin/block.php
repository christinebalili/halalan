<?php
	if ($action == 'edit')
	{
		$url = 'admin/blocks/edit/' . $block['id'];
		$icon = '<i class="icon-pencil"></i>';
	}
	else
	{
		$url = 'admin/blocks/add';
		$icon = '<i class="icon-plus"></i>';
	}
?>
<h1><?php echo e('admin_blocks_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li><?php echo anchor('admin/blocks', '<i class="icon-list"></i> List all'); ?></li>
		<li class="active">
			<?php echo anchor($url, $icon . ' ' . e('admin_' . $action . '_block_submit')); ?>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2><?php echo e('admin_' . $action . '_block_label'); ?></h2>
			<?php echo display_messages($this->session->flashdata('messages')); ?>
			<?php echo form_open($url, 'class="form-horizontal selectChosen"'); ?>
				<fieldset>
					<div class="control-group<?php echo form_error('block') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_block_block') . ':', 'block', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo form_input('block', set_value('block', $block['block']), 'id="block" maxlength="63"'); ?>
							<span class="help-inline"><?php echo form_error('block') ? form_error('block', ' ', ' ') : '(required)'; ?></span>
						</div>
					</div>
					<div class="control-group<?php echo form_error('chosen_elections') ? ' error' : ''; ?>">
						<?php echo form_label(e('admin_block_elections') . ':', 'possible_elections', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php if (empty($elections)): ?>
							<em><?php echo e('admin_block_no_elections'); ?></em>
							<?php else: ?>
							<table>
								<tr>
									<td>
										<?php echo form_dropdown('possible_elections[]', (count($possible_elections)) ? $possible_elections : array(), '', 'id="possible_elections" multiple="multiple" size="8" style="width : 210px;"'); ?>
										<br />
										<?php echo form_label(e('admin_block_possible_elections'), 'possible_elections'); ?>
									</td>
									<td>
										<input type="button" class="copySelectedWithAjax" value="  &gt;&gt;  " />
										<br />
										<input type="button" class="copySelectedWithAjax" value="  &lt;&lt;  " />
									</td>
									<td>
										<?php echo form_dropdown('chosen_elections[]', (count($chosen_elections)) ? $chosen_elections : array(), '', 'id="chosen_elections" multiple="multiple" size="8" style="width : 210px;"'); ?>
										<br />
										<?php echo form_label(e('admin_block_chosen_elections'), 'chosen_elections'); ?>
									</td>
									<td>
										<span class="help-inline"><?php echo form_error('chosen_elections') ? form_error('chosen_elections', ' ', ' ') : '(required)'; ?></span>
									</td>
								</tr>
							</table>
							<?php endif; ?>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_block_general_positions') . ':', 'general_positions', array('class' => 'control-label')); ?>
						<div class="controls">
							<table>
								<tr>
									<td>
										<?php echo form_dropdown('general_positions[]', (count($general_positions)) ? $general_positions : array(), '', 'id="general_positions" multiple="multiple" size="8" style="width : 210px;" disabled="disabled"'); ?>
									</td>
									<td valign="top">
										Notes:<br />
										The number inside the () is an election ID.<br />
										Positions are related to elections via the election ID.
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="control-group">
						<?php echo form_label(e('admin_block_specific_positions') . ':', 'possible', array('class' => 'control-label')); ?>
						<div class="controls">
							<table>
								<tr>
									<td>
										<?php echo form_dropdown('possible_positions[]', (count($possible_positions)) ? $possible_positions : array(), '', 'id="possible" multiple="multiple" size="8" style="width : 210px;"'); ?>
										<br />
										<?php echo form_label(e('admin_block_possible_positions'), 'possible'); ?>
									</td>
									<td>
										<input type="button" class="copySelected" value="  &gt;&gt;  " />
										<br />
										<input type="button" class="copySelected" value="  &lt;&lt;  " />
									</td>
									<td>
										<?php echo form_dropdown('chosen_positions[]', (count($chosen_positions)) ? $chosen_positions : array(), '', 'id="chosen" multiple="multiple" size="8" style="width : 210px;"'); ?>
										<br />
										<?php echo form_label(e('admin_block_chosen_positions'), 'chosen'); ?>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<?php echo form_submit('submit', e('admin_' . $action . '_block_submit'), 'class="btn btn-primary"') ?>
					<?php echo anchor('admin/blocks', 'Cancel', 'class="btn"'); ?>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>