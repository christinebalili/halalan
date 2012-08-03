<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title; ?> - Administration - Halalan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="<?php echo base_url('public/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('public/css/admin.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('public/css/bootstrap-responsive.min.css'); ?>" rel="stylesheet">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="<?php echo base_url('public/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('public/js/jquery.cookie.js'); ?>"></script>
    <script src="<?php echo base_url('public/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript">
        var BASE_URL = '<?php echo base_url(); ?>';
        var SITE_URL = '<?php echo site_url(); ?>';
        var CURRENT_URL = '<?php echo current_url(); ?>';
    </script>
    <script src="<?php echo base_url('public/js/admin.js'); ?>"></script>
  </head>
  <body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <?php echo anchor('admin/home', 'Halalan', 'class="brand"'); ?>
          <div class="nav-collapse">
            <ul class="nav">
              <?php
                $home = $elections = $positions = $parties = $candidates = $blocks = $voters = '';
                $active = $this->uri->segment(2);
                $$active = ' class="active"';
              ?>
              <li<?php echo $home; ?>><?php echo anchor('admin/home', 'Home', 'title="Home"'); ?></li>
              <li<?php echo $elections; ?>><?php echo anchor('admin/elections', 'Elections', 'title="Manage Elections"'); ?></li>
              <li<?php echo $positions; ?>><?php echo anchor('admin/positions', 'Positions', 'title="Manage Positions"'); ?></li>
              <li<?php echo $parties; ?>><?php echo anchor('admin/parties', 'Parties', 'title="Manage Parties"'); ?></li>
              <li<?php echo $candidates; ?>><?php echo anchor('admin/candidates', 'Candidates', 'title="Manage Candidates"'); ?></li>
              <li<?php echo $blocks; ?>><?php echo anchor('admin/blocks', 'Blocks', 'title="Manage Blocks"'); ?></li>
              <li<?php echo $voters; ?>><?php echo anchor('admin/voters', 'Voters', 'title="Manage Voters"'); ?></li>
            </ul>
            <?php echo anchor('gate/logout', 'Logout (' . $this->session->userdata('username') . ')', 'id="user-info" class="pull-right"'); ?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <div class="container">
      <?php echo $body; ?>
    </div> <!-- /container -->
    <div class="footer">
      <div class="container">
        <div class="row">
          <div class="span8">
            <p><a href="http://halalan.uplug.org/">Halalan</a> is a free and open-souce voting system built by <a href="http://uplug.org/">UnPLUG</a>.</p>
            <p>With help from <a href="http://codeigniter.com/">CodeIgniter</a>, <a href="http://jquery.com/">jQuery</a>, <a href="http://twitter.github.com/bootstrap/">Twitter Bootstrap</a>, and <a href="http://glyphicons.com/">Glyphicon icons</a>.</p>
          </div>
          <div class="span4">
            <a href="#" class="pull-right"><i class="icon-arrow-up"></i>back to top</a>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>