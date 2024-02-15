<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- google single sign in api -->
       <script src="https://accounts.google.com/gsi/client" async></script>
        <meta name="google-signin-client_id" content="24488454500-plsc9b8j27sd1kkcmu217lgu4j040itl.apps.googleusercontent.com">
       <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content=" <?= SCHOOL?>">
		<meta name="keywords" content="Imo state, Agriculture, food, university, University of Agriculture, IMSUAES">
        <meta name="author" content=" <?= SCHOOL?>">
        <meta name="robots" content="noindex, dofollow">
          <?= $this->Html->meta('icon') ?>
		<?= $this->Html->charset() ?>
         
         <title> <?php mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');
        echo (!isset($title)) ? $this->fetch("title") : $title;
?> |  <?= SCHOOL?></title>
		
		<!-- Bootstrap CSS -->
                <?= $this->Html->css(['../assets/css/bootstrap.min','../assets/css/font-awesome.min',
                   'line-awesome.min', 'select2.min','style_1','bootstrap-datepicker.min']) ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>

    </head>
    <body class="account-page">
	
		<!-- Main Wrapper -->
        <div class="main-wrapper">
			 <?= $this->Flash->render() ?>
    
        <?= $this->fetch('content') ?>	
            
            <div class="col-md-12"><center> Powered By <a target="_blank" title="Netpro international Limited" href="https://www.netpro.africa">Netpro International Limited</a> </center></div>
                    
        </div>
                
		<!-- /Main Wrapper -->
		
		<!-- jQuery -->
                	<!-- /Main Wrapper -->
		   <?= $this->Html->script(
            ['../assets/js/jquery-3.2.1.min','../assets/js/popper.min','../assets/js/bootstrap.min',
                '../assets/js/app','select2.full.min','bootstrap-datepicker.min'])
    ?>
    <?= $this->fetch('script') ?>
		
                         <script>
                    
               $(document).ready(function () {
                $(".select2_single").select2({
                    placeholder: "Select One",
                    allowClear: true
                });
                $(".select2_group").select2({});
                $(".select2_multiple").select2({
                   // maximumSelectionLength: 14,
                   // placeholder: "With Max Selection limit 14",
                    allowClear: true
                });
            }); 
            
                //Date picker
            $('#datepicker').datepicker({
                autoclose: true
            });
                    
                    </script>
    </body>
</html>