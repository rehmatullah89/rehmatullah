<?php
require_once('auth.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Sanva.com</title>
<meta name="description" content="Place your description here">
<meta name="keywords" content="put, your, keyword, here">
<meta name="author" content="Templates.com - website templates provider">
<meta charset="utf-8">
<link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
<link rel="stylesheet" href="css/layout.css" type="text/css" media="all">
<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
<link rel="stylesheet" href="css/table.css" type="text/css" media="all">
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/cufon-replace.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_300.font.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_400.font.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_600.font.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<!--[if lt IE 7]>
<script type="text/javascript" src="http://info.template-help.com/files/ie6_warning/ie6_script_other.js"></script>
 <![endif]-->
<!--[if lt IE 9]>
<script type="text/javascript" src="js/html5.js"></script>
<![endif]-->
<style type="text/css">
  
 /* @page :first {
 	margin-top: 20mm; 
  }*/
  
  @page 
  {
    size: auto;   /* auto is the current printer page size */
    margin-top: 10mm;  /* this affects the margin in the printer settings */
    margin-bottom: 20mm;  /* this affects the margin in the printer settings */
    counter-increment: page;
    counter-reset: page 1;
    
  }

  #page-number:after {
    counter-increment: page_number;
    content: "Page " counter(page_number);
 }
  
  body 
  {
     background-color:#FFFFFF; 
     margin: 0px;  /* the margin on the content before printing */
  }
 

table.imagetable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 2px;
	border-color: #999999;
	border-collapse: collapse;
}
table.imagetable th {
	background:#b5cfd2 url('images/cell-blue.jpg');
	border-width: 2px;
	padding: 5px;
	border-style: solid;
	border-color: #999999;
}
table.imagetable td {
	background:#dcddc0 url('images/cell-grey.jpg');
	border-width: 1px;
	border-width: 1px;
	padding: 10px;
	border-style: solid;
	border-color: #999999;
}
</style>
</head>
<body id="page3">
<div class="tail-top2">
<!-- header -->
	<header>
		<div class="container">
			<div class="header-box">
				<div class="left">
					<div class="right">
						<nav>
							<ul>
										<li <?php if ($_GET['tab'] == 'home') echo 'class="current"'; ?>><a href="home.php?tab=home">Home</a></li>
							<li <?php if ($_GET['tab'] == 'distributors') echo 'class="current"'; ?>><a href="distributors.php?tab=distributors">Distributors</a></li>
							<li <?php if ($_GET['tab'] == 'products') echo 'class="current"'; ?>><a href="products.php?tab=products">Products</a></li>
							<li <?php if ($_GET['tab'] == 'reports') echo 'class="current"'; ?>><a href="reports.php?tab=reports">Reports</a></li>
							<li <?php if ($_GET['tab'] == 'orders') echo 'class="current"'; ?>><a href="orders.php?tab=orders">Orders</a></li>
							<li <?php if ($_GET['tab'] == 'emails') echo 'class="current"'; ?>><a href="emails.php?tab=emails">Emails</a></li>
							</ul>
						</nav>
						<h1><a href="home.php"><span>Sanva</span><font style="color: red;">KEC</font>.com</a></h1>
					</div>
				</div>
			</div>
			<span class="top-info">Welcome <b><?php echo $_SESSION['SESS_FIRST_NAME']." ".$_SESSION['SESS_LAST_NAME'];?></b> </span>
			
		</div>
	</header>