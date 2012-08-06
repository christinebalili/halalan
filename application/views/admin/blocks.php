<h1><?php echo e('admin_blocks_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><?php echo anchor('admin/blocks', '<i class="icon-list"></i> List all'); ?></li>
		<li><?php echo anchor('admin/blocks/add', '<i class="icon-plus"></i> ' . e('admin_blocks_add')); ?></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2>Showing list of blocks</h2>
			<form>
				<fieldset>
					<i>Filter blocks by <?php echo form_dropdown('election_id', array('' => 'Choose Election') + $elections, $election_id, 'class="changeElections"'); ?></i>
				</fieldset>
			</form>
			<?php echo display_messages($this->session->flashdata('messages')); ?>
			<table class="table table-bordered table-striped table-highlight">
				<thead>
					<tr class="center">
						<th><?php echo e('common_id'); ?></th>
						<th><?php echo e('admin_blocks_block'); ?></th>
						<th><?php echo e('admin_blocks_description'); ?></th>
						<th><?php echo e('common_actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($blocks)): ?>
					<tr>
						<td colspan="4" class="center"><em><?php echo e('admin_blocks_no_blocks'); ?></em></td>
					</tr>
					<?php else: ?>
					<?php foreach ($blocks as $block): ?>
					<tr>
						<td class="center">
							<?php echo $block['id']; ?>
						</td>
						<td>
							<?php echo anchor('admin/blocks/edit/' . $block['id'], $block['block']); ?>
						</td>
						<td>
							<?php echo nl2br($block['description']); ?>
						</td>
						<td class="center">
							<?php echo anchor('admin/blocks/edit/' . $block['id'], '<i class="icon-pencil"></i> Edit details', 'title="Edit details" class="action btn btn-small"'); ?>
							<?php echo anchor('admin/blocks/delete/' . $block['id'], '<i class="icon-trash icon-white"></i> Delete block', 'title="Delete block" class="action btn btn-small btn-danger confirmDelete"'); ?>
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