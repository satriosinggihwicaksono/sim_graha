<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="<?php echo base_url();?>assets/images/logo.png">	
  <title>GrahaCCTV | Sistem Informasi Management</title>
  <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/animate.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/app.css" type="text/css" />
  <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js"></script>
    <script src="js/ie/respond.min.js"></script>
    <script src="js/ie/excanvas.js"></script>
  <![endif]-->
</head>
<body>
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">    
    <div class="container aside-xxl">
      <a class="navbar-brand block" href="index.html">Grahacom CCTV</a>
     <?php
			$segment_url = $this->uri->segment(2);
			$error = validation_errors();
			if(empty($segment_url)){
				include 'sigup.php'; 
			} elseif($error && $segment_url === 'registration'){
				include 'register.php';
			} elseif($segment_url === 'register'){
				include 'register.php';	
			} elseif($error && $segment_url === 'sigup') {
				include 'sigup.php';
			} else {
				include 'sigup.php';
			}
		?>
    </div>
  </section>
  <!-- footer -->
  <footer id="footer">
    <div class="text-center padder">
    </div>
  </footer>
  <!-- / footer -->
  <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
  <!-- App -->
  <script src="<?php echo base_url();?>assets/js/app.js"></script>
  <script src="<?php echo base_url();?>assets/js/app.plugin.js"></script>
  <script src="<?php echo base_url();?>assets/js/slimscroll/jquery.slimscroll.min.js"></script>
  
</body>
</html>