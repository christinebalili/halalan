<?php echo display_messages(validation_errors('<li>', '</li>'), $this->session->flashdata('messages')); ?>
<?php if ($action == 'add'): ?>
	<?php echo form_open('admin/elections/add'); ?>
<?php elseif ($action == 'edit'): ?>
	<?php echo form_open('admin/elections/edit/' . $election['id']); ?>
<?php endif; ?>
<h2><?php echo e('admin_' . $action . '_election_label'); ?></h2>
<table cellpadding="0" cellspacing="0" border="0" class="form_table" width="100%">
	<tr>
		<td class="w20" align="right">
			<?php echo form_label(e('admin_election_election') . ':', 'election'); ?>
		</td>
		<td>
			<?php echo form_input('election', set_value('election', $election['election']), 'id="election" maxlength="63" class="text"'); ?>
		</td>
	</tr>
	<tr>
		<td class="w20" align="right">
			<?php echo form_label(e('admin_election_description') . ':', 'description'); ?>
		</td>
		<td>
			<?php echo form_textarea('description', set_value('description', $election['description']), 'id="description"'); ?>
		</td>
	</tr>
</table>
<div class="paging">
	<?php echo anchor('admin/elections', 'GO BACK'); ?>
	|
	<?php echo form_submit('submit', e('admin_' . $action . '_election_submit')) ?>
</div>
<?php echo form_close(); ?>