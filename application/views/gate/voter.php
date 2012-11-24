<div class="row-fluid marketing">
  <div class="span12">
    <?php echo form_open('gate/voter', 'class="form-horizontal"'); ?>
      <?php echo display_messages($this->session->flashdata('messages')); ?>
      <?php echo form_input_html('username', '', '', '(required)', 'gate_voter_username'); ?>
      <?php echo form_password_html('password', '', '', '(required)', 'gate_voter_password'); ?>
      <?php echo form_submit_html(e('gate_voter_login_button'), FALSE); ?>
    <?php echo form_close(); ?>
  </div>
</div>