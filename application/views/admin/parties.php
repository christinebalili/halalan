<h1><?php echo e('admin_parties_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><?php echo anchor('admin/parties', '<i class="icon-list"></i> List all'); ?></li>
		<li><?php echo anchor('admin/parties/add', '<i class="icon-plus"></i> ' . e('admin_parties_add')); ?></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2>Showing list of parties</h2>
			<form>
				<fieldset>
					<i>Filter parties by <?php echo form_dropdown('election_id', array('' => 'Choose Election') + $elections, $election_id, 'class="changeElections"'); ?></i>
				</fieldset>
			</form>
			<?php echo display_messages($this->session->flashdata('messages')); ?>
			<table class="table table-bordered table-striped table-highlight">
				<thead>
					<tr class="center">
						<th><?php echo e('common_id'); ?></th>
						<th><?php echo e('admin_parties_party'); ?></th>
						<th><?php echo e('admin_parties_alias'); ?></th>
						<th><?php echo e('admin_parties_description'); ?></th>
						<th><?php echo e('common_actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($parties)): ?>
					<tr>
						<td colspan="5" class="center"><em><?php echo e('admin_parties_no_parties'); ?></em></td>
					</tr>
					<?php else: ?>
					<?php foreach ($parties as $party): ?>
					<tr>
						<td class="center">
							<?php echo $party['id']; ?>
						</td>
						<td>
							<?php echo anchor('admin/parties/edit/' . $party['id'], $party['party']); ?>
						</td>
						<td class="center">
							<?php echo $party['alias']; ?>
						</td>
						<td class="center">
							<?php echo nl2br($party['description']); ?>
						</td>
						<td class="center">
							<?php echo anchor('admin/parties/edit/' . $party['id'], '<i class="icon-pencil"></i> Edit details', 'title="Edit details" class="action btn btn-small"'); ?>
							<?php echo anchor('admin/parties/delete/' . $party['id'], '<i class="icon-trash icon-white"></i> Delete party', 'title="Delete party" class="action btn btn-small btn-danger confirmDelete"'); ?>
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