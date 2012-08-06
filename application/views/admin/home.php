<div class="row">
	<div class="span6">
		<h2><?php echo e('admin_home_left_label'); ?></h2>
		<div class="notes">
			<h2><?php echo e('admin_home_manage_question'); ?></h2>
			<ul>
				<li><?php echo anchor('admin/elections', e('admin_home_manage_elections')); ?></li>
				<li><?php echo anchor('admin/positions', e('admin_home_manage_positions')); ?></li>
				<li><?php echo anchor('admin/parties', e('admin_home_manage_parties')); ?></li>
				<li><?php echo anchor('admin/candidates', e('admin_home_manage_candidates')); ?></li>
				<li><?php echo anchor('admin/blocks', e('admin_home_manage_blocks')); ?></li>
				<li><?php echo anchor('admin/voters', e('admin_home_manage_voters')); ?></li>
			</ul>
		</div>
	</div>
	<div class="span6">
		<h2><?php echo e('admin_home_right_label'); ?></h2>
		<?php echo display_messages($this->session->flashdata('messages')); ?>
		<?php echo form_open('admin/home/do_regenerate', 'class="form-horizontal"'); ?>
			<fieldset>
				<div class="control-group">
					<?php echo form_label($this->config->item('halalan_password_pin_generation') == 'email' ? e('admin_home_email') : e('admin_home_username'), 'username', array('class' => 'control-label')); ?>
					<div class="controls">
						<?php echo form_input('username', '', 'id="username" maxlength="63"'); ?>
					</div>
				</div>
				<?php if ($this->config->item('halalan_pin')): ?>
				<div class="control-group">
					<div class="controls">
						<label class="checkbox inline">
							<?php echo form_checkbox('pin', TRUE, FALSE, 'id="pin"'); ?>
							&nbsp;<?php echo e('admin_home_pin'); ?>
						</label>
					</div>
				</div>
				<?php endif; ?>
				<div class="control-group">
					<div class="controls">
						<label class="checkbox inline">
							<?php echo form_checkbox('login', TRUE, FALSE, 'id="login"'); ?>
							&nbsp;<?php echo e('admin_home_login'); ?>
						</label>
					</div>
				</div>
			</fieldset>
			<div class="form-actions">
				<?php echo form_submit('submit', e('admin_home_submit')); ?>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>