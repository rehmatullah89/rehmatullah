<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>sanva.com</title>
<meta name="description" content="Place your description here">
<meta name="keywords" content="put, your, keyword, here">
<meta charset="utf-8">
<link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
<link rel="stylesheet" href="css/layout.css" type="text/css" media="all">
<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
<script type="text/javascript" src="js/maxheight.js"></script>
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/cufon-replace.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_300.font.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_400.font.js"></script>
<script type="text/javascript" src="js/jquery.faded.js"></script>
<script type="text/javascript" src="js/jquery.jqtransform.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript">
	$(function(){
		$("#faded").faded({
			speed: 500,
			crossfade: true,
			autoplay: 10000,
			autopagination:false
		});
		
		$('#domain-form').jqTransform({imgPath:'jqtransformplugin/img/'});
	});
</script>
<!--[if lt IE 7]>
<script type="text/javascript" src="http://info.template-help.com/files/ie6_warning/ie6_script_other.js"></script>
<![endif]-->
<!--[if lt IE 9]>
<script type="text/javascript" src="js/html5.js"></script>
<![endif]-->

<style>
form {
 	background:#111; 
  width:300px;
  margin:30px auto;
  border-radius:0.4em;
  border:1px solid #191919;
  overflow:hidden;
  position:relative;
  box-shadow: 0 5px 10px 5px rgba(0,0,0,0.2);
}

form:after {
  content:"";
  display:block;
  position:absolute;
  height:1px;
  width:100px;
  left:20%;
  background:linear-gradient(left, #111, #444, #b6b6b8, #444, #111);
  top:0;
}

form:before {
 	content:"";
  display:block;
  position:absolute;
  width:8px;
  height:5px;
  border-radius:50%;
  left:34%;
  top:-7px;
  box-shadow: 0 0 6px 4px #fff;
}

.inset {
 	padding:20px; 
  border-top:1px solid #19191a;
}

form h1 {
  font-size:18px;
  text-shadow:0 1px 0 black;
  text-align:center;
  padding:15px 0;
  border-bottom:1px solid rgba(0,0,0,1);
  position:relative;
}

form h1:after {
 	content:"";
  display:block;
  width:250px;
  height:100px;
  position:absolute;
  top:0;
  left:50px;
  pointer-events:none;
  transform:rotate(70deg);
  background:linear-gradient(50deg, rgba(255,255,255,0.15), rgba(0,0,0,0));
  
}

label {
 	color:#666;
  display:block;
  padding-bottom:9px;
}

input[type=text],
input[type=password] {
 	width:100%;
  padding:8px 5px;
  background:linear-gradient(bottom, rgb(115,59,210) 26%, rgb(60,158,158) 63%);
  border:1px solid #222;
  box-shadow:
    0 1px 0 rgba(255,255,255,0.1);
  border-radius:0.3em;
  margin-bottom:20px;
}

label[for=remember]{
 	color:white;
  display:inline-block;
  padding-bottom:0;
  padding-top:5px;
}

input[type=checkbox] {
 	display:inline-block;
  vertical-align:top;
}

.p-container {
 	padding:0 20px 20px 20px; 
}

.p-container:after {
 	clear:both;
  display:table;
  content:"";
}

.p-container span {
  display:block;
  float:left;
  color:#0d93ff;
  padding-top:8px;
}

input[type=submit] {
 	padding:5px 20px;
  border:1px solid rgba(0,0,0,0.4);
  text-shadow:0 -1px 0 rgba(0,0,0,0.4);
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,0.3),
    inset 0 10px 10px rgba(255,255,255,0.1);
  border-radius:0.3em;
  background:#0184ff;
  color:white;
  float:right;
  font-weight:bold;
  cursor:pointer;
  font-size:13px;
}

input[type=submit]:hover {
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,0.3),
    inset 0 -10px 10px rgba(255,255,255,0.1);
}

input[type=text]:hover,
input[type=password]:hover,
label:hover ~ input[type=text],
label:hover ~ input[type=password] {
 	background:grey;
}
</style>
</head>
<body id="page1" onLoad="new ElementMaxHeight();">
<div class="tail-top">
<!-- header -->
	<header>
		<div class="container">
			<div class="header-box">
				<div class="left">
					<div class="right">
						
						<h1><a href="index.php"><span>Sanva</span>KEC.com</a></h1>
					</div>
				</div>
			</div>
			<span class="top-info">24/7 Sales &amp; Support	+ 1 800 234 5678  &nbsp; l  &nbsp; </span>
			
		</div>
	</header>
<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside">
			<?php 
			if(!empty($_SESSION['message']))
			echo "<h3>".$_SESSION['message']."</h3>";
			?>
				<form action="login-exec.php" method="post">
  <h1>Administrator Log-in</h1>
  <div class="inset">
  <p>
    <label for="email">EMAIL ADDRESS</label>
    <input type="text" name="email" id="email" onFocus="if(this.value=='email'){this.value=''}" onBlur="if(this.value==''){this.value='email'}">
  </p>
  <p>
    <label for="password">PASSWORD</label>
    <input type="Password" name="Password" id="password" onFocus="if(this.value=='Password'){this.value=''}" onBlur="if(this.value==''){this.value='Password'}">
  </p>
    </div>
  <p class="p-container">
    <span><?php
	if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
		echo '<ul class="err">';
		foreach($_SESSION['ERRMSG_ARR'] as $msg) {
			echo "<li style='color:red;'>",$msg,'</li>'; 
		}
		echo '</ul>';
		unset($_SESSION['ERRMSG_ARR']);
	}
	?></span>
    <input type="submit" name="go" id="go" value="Log in">
  </p>
</form>
</br>
</br>
</br>
</br>
</br>
					</div>
				</div>
					

</section>
</div>


<!-- ---->


<!-- footer -->
<footer>
	<div class="container">
		<div class="inside">
			All rights reserved @<a class="new_window" href="http://www.sanva.com" target="_blank" rel="nofollow">www.sanva.com</a><br/>
			</div>
	</div>
</footer>
<script type="text/javascript"> Cufon.now(); </script>
</body>
</html>