<!-- Template from http://twitter.github.com/bootstrap/examples/marketing-narrow.html -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Halalan Installer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Le styles -->
    <link href="<?php echo base_url('public/css/bootstrap.min.css'); ?>" rel="stylesheet" media="screen">
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
      }
      .container-narrow {
        margin: 0 auto;
        max-width: 750px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }
      .footer {
        text-align: center;
      }
    </style>
    <link href="<?php echo base_url('public/css/bootstrap-responsive.min.css'); ?>" rel="stylesheet" media="screen">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <script src="<?php echo base_url('public/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('public/js/bootstrap.min.js'); ?>"></script>
  </head>

  <body>

    <div class="container-narrow">

      <h3 class="muted">Halalan Installer</h3>

      <hr>

      <?php if ( ! empty($password)): ?>
      <div class="alert alert-success">
        <p><strong>Congratulations!</strong>  You have successfully installed Halalan.</p>
        <p>Here is your password: <strong><?php echo $password; ?></strong></p>
        <p><strong>Note:</strong> Please remove this installer before running Halalan.</p>
      </div>
      <?php endif; ?>

      <?php echo form_open('', 'class="form-horizontal"'); ?>
        <?php echo form_input_html('username', '', '', '(required)', ''); ?>
        <?php echo form_input_html('email', '', '', '(required)', ''); ?>
        <?php echo form_input_html('name', '', '', '(required)', ''); ?>
        <?php echo form_submit_html('Install', FALSE); ?>
      <?php echo form_close(); ?>

      <hr>

      <div class="footer">
        <p>Powered by <a href="http://halalan.uplug.org/">Halalan <?php echo HALALAN_VERSION; ?></a></p>
      </div>

    </div> <!-- /container -->

  </body>
</html>
