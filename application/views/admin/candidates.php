<h1><?php echo e('admin_candidates_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><?php echo anchor('admin/candidates', '<i class="icon-list"></i> List all'); ?></li>
		<li><?php echo anchor('admin/candidates/add', '<i class="icon-plus"></i> ' . e('admin_candidates_add')); ?></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2>Showing list of candidates</h2>
			<form>
				<fieldset>
					<i>
						Filter candidates by
						<?php echo form_dropdown('election_id', array('' => 'Choose Election') + $elections, $election_id, 'class="changeElections"'); ?>
						<?php echo form_dropdown('position_id', array('' => 'All Positions') + $positions, $position_id, 'class="changePositions"'); ?>
					</i>
				</fieldset>
			</form>
			<?php echo display_messages($this->session->flashdata('messages')); ?>
			<table class="table table-bordered table-striped table-highlight">
				<thead>
					<tr class="center">
						<th><?php echo e('common_id'); ?></th>
						<th><?php echo e('admin_candidates_candidate'); ?></th>
						<th><?php echo e('admin_candidates_description'); ?></th>
						<th>Position</th>
						<th><?php echo e('common_actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($candidates)): ?>
					<tr>
						<td colspan="5" class="center"><em><?php echo e('admin_candidates_no_candidates'); ?></em></td>
					</tr>
					<?php else: ?>
					<?php foreach ($candidates as $candidate): ?>
					<tr>
						<td class="center">
							<?php echo $candidate['id']; ?>
						</td>
						<td>
							<?php echo anchor('admin/candidates/edit/' . $candidate['id'], $candidate['first_name'] . ' ' . $candidate['last_name']); ?>
						</td>
						<td>
							<?php echo nl2br($candidate['description']); ?>
						</td>
						<td>
						</td>
						<td class="center">
							<?php echo anchor('admin/candidates/edit/' . $candidate['id'], '<i class="icon-pencil"></i> Edit details', 'title="Edit details" class="action btn btn-small"'); ?>
							<?php echo anchor('admin/candidates/delete/' . $candidate['id'], '<i class="icon-trash icon-white"></i> Delete candidate', 'title="Delete candidate" class="action btn btn-small btn-danger confirmDelete"'); ?>
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