<div id="container">
	<?php echo form_open('gate/admin'); ?>
		<h1 class="center">
			<img src="<?php echo base_url('public/img/logo.png'); ?>" alt="Halalan Logo" />
		</h1>
		<p class="center">Administration</p>
		<?php echo display_messages($this->session->flashdata('messages')); ?>
		<label for="username">
			<?php echo e('gate_admin_username'); ?>
			<?php echo form_input('username', '', 'id="username" maxlength="63"'); ?>
		</label>
		<label for="password">
			<?php echo e('gate_admin_password'); ?>
			<?php echo form_password('password', '', 'id="password"'); ?>
		</label>
		<?php echo form_submit('submit', e('gate_admin_login_button'), 'class="btn btn-primary"'); ?>
	<?php echo form_close(); ?>
	<p class="center options">
		view:
		<?php echo anchor('gate/results', 'results'); ?> |
		<?php echo anchor('gate/statistics', 'statistics'); ?> |
		<?php echo anchor('gate/ballots', 'ballots'); ?>
	</p>
</div>	