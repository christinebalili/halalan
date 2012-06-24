<div id="container">
	<?php echo form_open('gate/admin'); ?>
		<h1>
			<?php echo anchor('http://halalan.uplug.org/', 'Halalan ' . e('gate_admin_login_label')); ?>
			<sup><?php echo anchor('http://uplug.org/', 'by UnPLUG'); ?></sup>
		</h1>
		
		<?php echo display_messages('', $this->session->flashdata('messages')); ?>
		
		<label for="username">
			<?php echo e('gate_admin_username'); ?>
			<?php echo form_input('username', '', 'id="username" maxlength="63" class="text"'); ?>
		</label>
		
		<label for="password">
			<?php echo e('gate_admin_password'); ?>
			<?php echo form_password('password', '', 'id="password" class="text"'); ?>
		</label>
		
		<?php echo form_submit('submit', e('gate_admin_login_button'), 'class="btn btn-primary"'); ?>
	<?php echo form_close(); ?>
	
	<p class="center options">
		<a href="#">&larr; View Results</a> |
		<a href="#">Get Statistics &rarr;</a>
	</p>
</div>	