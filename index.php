<?php 
	include('./vendor/autoload.php');
	use app\dbconn;
	use app\menu;
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Home Page</title>
 	<link rel="stylesheet" type="text/css" href="styles/reset.css">
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
 </head>
 <body>
 	<div class="holder">
		<div class="header">
			<div class="logo">
				
			</div>
		</div>
		
		<div class="menu">
			<?php
				try {
  				$build = new Menu;
 				echo $build->displayMenu();
				} catch (Exception $e) {
  				echo $e->getMessage().PHP_EOL.$e->getTraceAsString();
				}
			?>
		</div>
		
		<div class="content">
			
		</div>
	</div>

	
	<div class="footer clearfix">
		
	</div>
 </body>
 </html>