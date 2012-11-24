<div class="row-fluid marketing">
  <div class="span12">
    <?php echo form_open('gate/admin', 'class="form-horizontal"'); ?>
      <?php echo display_messages($this->session->flashdata('messages')); ?>
      <?php echo form_input_html('username', '', '', '(required)', 'gate_admin_username'); ?>
      <?php echo form_password_html('password', '', '', '(required)', 'gate_admin_password'); ?>
      <?php echo form_submit_html(e('gate_admin_login_button'), FALSE); ?>
    <?php echo form_close(); ?>
  </div>
</div>