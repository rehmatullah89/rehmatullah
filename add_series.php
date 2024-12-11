<?php
include("header_addForm.php");
?>
<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside">
				
		
				<div class="inside1">
					<div class="wrap">
						<?php
						if(!empty($_SESSION['register']))
						echo "<h1>".$_SESSION['register']."</h1>";
	if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
		echo '<ul class="err">';
		foreach($_SESSION['ERRMSG_ARR'] as $msg) {
			echo "<li style='color:red;'>",$msg,'</li>'; 
		}
		echo '</ul>';
		unset($_SESSION['ERRMSG_ARR']);
	}
?>
							<br/><br/>
							<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
							<div id="registration" style="width:800px; margin:0 auto;">
 <h2>Add New Series Name</h2>

 <form id="RegisterUserForm" action="add_series-exec.php" method="post">
 	<fieldset>
         <p>
            <label for="name">Series Name</label>
            <input id="series_name" name="series_name" type="text" class="text" value="" />
         </p>
          
         <p>
            <button id="addSeries" name="addSeries" type="submit">Add Series</button>
         </p>
 	</fieldset>

 </form>
</div>
					
						<div class="clear"></div>
					</div>
				</div>
						</div>
	</div>
</section>
</div>
<?php
include("footer.php");
?>		
