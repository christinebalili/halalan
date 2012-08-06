<h1><?php echo e('admin_voters_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><?php echo anchor('admin/voters', '<i class="icon-list"></i> List all'); ?></li>
		<li><?php echo anchor('admin/voters/add', '<i class="icon-plus"></i> ' . e('admin_voters_add')); ?></li>
		<li><?php echo anchor('admin/voters/import', '<i class="icon-download"></i> Import Voters'); ?></li>
		<li><?php echo anchor('admin/voters/export', '<i class="icon-upload"></i> Export Voters'); ?></li>
		<li><?php echo anchor('admin/voters/generate', '<i class="icon-wrench"></i> ' . ($this->config->item('halalan_pin') ? 'Generate Passwords and PINs' : 'Generate Passwords')); ?></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2>Showing list of voters</h2>
			<form>
				<fieldset>
					<i>Filter voters by <?php echo form_dropdown('block_id', array('' => 'Choose Block') + $blocks, $block_id, 'class="changeBlocks"'); ?></i>
				</fieldset>
			</form>
			<?php echo display_messages($this->session->flashdata('messages')); ?>
			<table class="table table-bordered table-striped table-highlight">
				<thead>
					<tr class="center">
						<th><?php echo e('common_id'); ?></th>
						<th><?php echo e('admin_voters_name'); ?></th>
						<th><?php echo e('common_actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($voters)): ?>
					<tr>
						<td colspan="3" class="center"><em><?php echo e('admin_voters_no_voters'); ?></em></td>
					</tr>
					<?php else: ?>
					<?php foreach ($voters as $voter): ?>
					<tr>
						<td class="center">
							<?php echo $voter['id']; ?>
						</td>
						<td>
							<?php echo anchor('admin/voters/edit/' . $voter['id'], $voter['last_name'] . ', ' . $voter['first_name']); ?>
						</td>
						<td class="center">
							<?php echo anchor('admin/voters/edit/' . $voter['id'], '<i class="icon-pencil"></i> Edit details', 'title="Edit details" class="action btn btn-small"'); ?>
							<?php echo anchor('admin/voters/delete/' . $voter['id'], '<i class="icon-trash icon-white"></i> Delete voter', 'title="Delete voter" class="action btn btn-small btn-danger confirmDelete"'); ?>
						</td>
					</tr>
					<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php // put other tab contents here when using JS to activate them ?>
	</div>
</div>