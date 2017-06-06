<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CRUD Generator</title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>aset/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>aset/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <!-- jQuery -->
    <script src="<?php echo base_url('aset/vendors/jquery/dist/jquery.min.js'); ?>"></script>
    <script type="text/javascript">
      base_url = "<?php echo base_url(); ?>";  
    </script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url(); ?>aset/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->

  </head>
  <body>

  <div class="container" style="margin-top: 20px">
  <?php echo $this->load->view($p); ?>
  </div>
  </body>
</html>