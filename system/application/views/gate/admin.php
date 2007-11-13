<?php if (isset($message) && !empty($message)): ?>
<div class="message">
	<?= $message; ?>
</div>
<?php endif; ?>
<?= form_open('gate/admin_login'); ?>
<div class="body">
	<div class="center_body" style="text-align : center;">
		<fieldset style="width : 350px; margin : 0 auto;">
			<legend class="position"><?= e('admin_login_label'); ?></legend>
			<table cellspacing="2" cellpadding="2" align="center">
				<tr>
					<td><?= e('username'); ?></td>
					<td><?= form_input(array('name'=>'username', 'maxlength'=>'63')); ?></td>
				</tr>
				<tr>
					<td><?= e('password'); ?></td>
					<td><?= form_password(array('name'=>'password')); ?></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><?= form_submit(array('value'=>e('login_button'))); ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clear"></div>
</div>
</form>