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
 <h2>Admin Change Password</h2>

 <form id="RegisterUserForm" action="change_password-exec.php" method="post">
 	<fieldset>
         <p>
            <label for="name">Old Password</label>
            <input id="old_pass" name="old_pass" type="text" class="text" value="" />
         </p>
         <p>
            <label for="name">New Password</label>
            <input id="new_pass" name="new_pass" type="text" class="text" value="" />
         </p>
         <p>
            <label for="name">New Password (Repeat)</label>
            <input id="new_pass2" name="new_pass2" type="text" class="text" value="" />
         </p> 		 
         <p>
            <button id="changePass" name="changePass" type="submit">Submit</button>
         </p>
 	</fieldset>
	<h2>Sanva User Change Password</h2>
	<fieldset>
         <p>
            <label for="name">Old Password</label>
            <input id="user1_old_pass" name="user1_old_pass" type="text" class="text" value="" />
         </p>
         <p>
            <label for="name">New Password</label>
            <input id="user1_new_pass" name="user1_new_pass" type="text" class="text" value="" />
         </p>
         <p>
            <label for="name">New Password (Repeat)</label>
            <input id="user1_new_pass2" name="user1_new_pass2" type="text" class="text" value="" />
         </p> 		 
         <p>
            <button id="changePass1" name="changePass1" type="submit">Submit</button>
         </p>
 	</fieldset>
	<h2>Dash User Change Password</h2>
	<fieldset>
         <p>
            <label for="name">Old Password</label>
            <input id="user2_old_pass" name="user2_old_pass" type="text" class="text" value="" />
         </p>
         <p>
            <label for="name">New Password</label>
            <input id="user2_new_pass" name="user2_new_pass" type="text" class="text" value="" />
         </p>
         <p>
            <label for="name">New Password (Repeat)</label>
            <input id="user2_new_pass2" name="user2_new_pass2" type="text" class="text" value="" />
         </p> 		 
         <p>
            <button id="changePass2" name="changePass2" type="submit">Submit</button>
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
