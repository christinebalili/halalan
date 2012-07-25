<h1><?php echo e('admin_positions_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><?php echo anchor('admin/positions', '<i class="icon-list"></i> List all'); ?></li>
		<li><?php echo anchor('admin/positions/add', '<i class="icon-plus"></i> ' . e('admin_positions_add')); ?></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2>Showing list of positions</h2>
			<form>
				<fieldset>
					<i>Filter positions by <?php echo form_dropdown('election_id', array('' => 'Choose Election') + $elections, $election_id, 'class="changeElections"'); ?></i>
				</fieldset>
			</form>
			<table class="table table-bordered table-striped table-highlight">
				<thead>
					<tr class="center">
						<th><?php echo e('common_id'); ?></th>
						<th><?php echo e('admin_positions_position'); ?></th>
						<th><?php echo e('admin_positions_description'); ?></th>
						<th><?php echo e('common_actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($positions)): ?>
					<tr>
						<td colspan="4" class="center"><em><?php echo e('admin_positions_no_positions'); ?></em></td>
					</tr>
					<?php else: ?>
					<?php foreach ($positions as $position): ?>
					<tr>
						<td class="center">
							<?php echo $position['id']; ?>
						</td>
						<td>
							<?php echo anchor('admin/positions/edit/' . $position['id'], $position['position']); ?>
						</td>
						<td class="center">
							<?php echo nl2br($position['description']); ?>
						</td>
						<td class="center">
							<?php echo anchor('admin/positions/edit/' . $position['id'], '<i class="icon-pencil"></i> Edit details', 'title="Edit details" class="action btn btn-small"'); ?>
							<?php echo anchor('admin/positions/delete/' . $position['id'], '<i class="icon-trash icon-white"></i> Delete position', 'title="Delete position" class="action btn btn-small btn-danger confirmDelete"'); ?>
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