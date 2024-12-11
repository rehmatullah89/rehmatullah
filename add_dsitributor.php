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
 <h2>Create New Distributor</h2>

 <form id="RegisterUserForm" action="reg_distributor-exec.php" method="post">
 	<fieldset>
         <p>
            <label for="name">Name</label>
            <input id="name" name="name" type="text" class="text" value="" />
         </p>
        
         <p>
            <label for="tel">Phone Number</label>
            <input id="tel" name="tel" type="tel" class="text" value="" />
         </p>
        
         <p>
            <label for="email">Address</label>
            <input id="address" name="address" type="text" class="text" value="" />
         </p>

         <p>
            <label for="dist_for_series">Distributor for Series</label>
            <select class="text"  name="dist_for_series">
            	<option value="sanva">Sanva</option>
            	<option value="dash">Dash</option>
            </select>
         </p>

         <p>
            <button id="registerNew" name="registerNew" type="submit">Register</button>
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
