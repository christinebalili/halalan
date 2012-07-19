<h1><?php echo e('admin_elections_label'); ?></h1>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><?php echo anchor('admin/elections', '<i class="icon-list"></i> List all'); ?></li>
		<li><?php echo anchor('admin/elections/add', '<i class="icon-plus"></i> ' . e('admin_elections_add')); ?></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active">
			<h2>Showing list of elections</h2>
			<table class="table table-bordered table-striped table-highlight">
				<thead>
					<tr class="center">
						<th><?php echo e('common_id'); ?></th>
						<th><?php echo e('admin_elections_election'); ?></th>
						<th><?php echo e('admin_elections_status'); ?></th>
						<th><?php echo e('admin_elections_results'); ?></th>
						<th><?php echo e('common_actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($elections)): ?>
					<tr>
						<td colspan="5" class="center"><em><?php echo e('admin_elections_no_elections'); ?></em></td>
					</tr>
					<?php else: ?>
					<?php $i = 0; ?>
					<?php foreach ($elections as $election): ?>
					<tr class="<?php echo ($i % 2 == 0) ? 'odd' : 'even'  ?>">
						<td class="center">
							<?php echo $election['id']; ?>
						</td>
						<td>
							<?php echo anchor('admin/elections/edit/' . $election['id'], $election['election']); ?>
						</td>
						<td class="center">
							<p>
							<?php if ($election['status']): ?>
								<span class="label label-success" title="This election is currently open">Running</span>
								<?php echo anchor('admin/elections/options/status/' . $election['id'], '<i class="icon-off"></i> Close this election', 'title="Close this election" class="btn btn-small"'); ?>
							<?php else: ?>
								<span class="label" title="This election is currently closed">Not Running</span>
								<?php echo anchor('admin/elections/options/status/' . $election['id'], '<i class="icon-off"></i> Start this election', 'title="Start this election" class="btn btn-small"'); ?>
							<?php endif; ?>
							</p>
						</td>
						<td class="center">
							<p>
							<?php if ($election['results']): ?>
								<span class="label label-success" title="Results are accessible by the public">Accessible</span>
								<?php echo anchor('admin/elections/options/results/' . $election['id'], '<i class="icon-eye-close"></i> Hide from public', 'title="Hide from public" class="btn btn-small"'); ?>
								<a href="#" ></a>
							<?php else: ?>
								<span class="label label" title="Results are available to public">Hidden</span>
								<?php echo anchor('admin/elections/options/results/' . $election['id'], '<i class="icon-eye-close"></i> Show to public', 'title="Show to public" class="btn btn-small"'); ?>
							<?php endif; ?>
							</p>
						</td>
						<td class="center">
							<?php echo anchor('admin/elections/edit/' . $election['id'], '<i class="icon-pencil"></i> Edit details', 'title="Edit details" class="action btn btn-small"'); ?>
							<?php echo anchor('admin/elections/delete/' . $election['id'], '<i class="icon-trash icon-white"></i> Delete election', 'title="Delete election" class="action btn btn-small btn-danger confirmDelete"'); ?>
						</td>
					</tr>
					<?php $i = $i + 1; ?>
					<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php // put other tab contents here when using JS to activate them ?>
	</div>
</div>