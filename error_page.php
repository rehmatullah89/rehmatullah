	<?php session_start();?>
	<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside">
			
					<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
				
				<div class="inside1">
		
					<?php
		echo "Go back and correct following errors"."<pre>".print_r($_SESSION['ERRMSG_ARR']);
					if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {

		echo '<ul class="err">';
		foreach($_SESSION['ERRMSG_ARR'] as $msg) {
			echo "<li style='color:red;'>",$msg,'</li>'; 
		}
		echo '</ul>';
		echo "Go back and correct following errors"."<pre>".print_r($msg);
		unset($_SESSION['ERRMSG_ARR']);

	}
?>
				</div>
			</div>
		</div>
	</section>
</div>
