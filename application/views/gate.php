<!-- Template from http://twitter.github.com/bootstrap/examples/marketing-narrow.html -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title; ?> - Halalan</title>
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
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
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

      <ul class="nav nav-pills pull-right">
        <li<?php echo $active == 'home' ? ' class="active"' : ''; ?>><?php echo anchor('gate', 'Home'); ?></li>
        <li<?php echo $active == 'voter' ? ' class="active"' : ''; ?>><?php echo anchor('gate/voter', 'Voter Login'); ?></li>
        <li<?php echo $active == 'admin' ? ' class="active"' : ''; ?>><?php echo anchor('gate/admin', 'Admin Login'); ?></li>
      </ul>
      <h3 class="muted">Halalan</h3>

      <hr>

      <?php echo $body; ?>

      <hr>

      <div class="footer">
        <p>Powered by <a href="http://halalan.uplug.org/">Halalan <?php echo HALALAN_VERSION; ?></a></p>
      </div>

    </div> <!-- /container -->

  </body>
</html>